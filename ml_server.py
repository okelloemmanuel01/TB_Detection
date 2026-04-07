import os
import base64
import numpy as np
import cv2
from fastapi import FastAPI, File, UploadFile
from fastapi.responses import JSONResponse
import tensorflow as tf

app = FastAPI()

MODEL_PATH = "/tmp/active_model.h5"
MODEL_URL = os.environ.get('MODEL_URL', '')

model = None

def load_model():
    global model
    if model is None:
        if not os.path.exists(MODEL_PATH):
            if not MODEL_URL:
                raise FileNotFoundError("MODEL_URL environment variable not set.")
            print(f"Downloading model from {MODEL_URL}...")
            _download_gdrive(MODEL_URL, MODEL_PATH)
            print(f"Model downloaded. Size: {os.path.getsize(MODEL_PATH)} bytes")
        model = tf.keras.models.load_model(MODEL_PATH)
        print("Model loaded successfully.")
    return model

def _download_gdrive(url, dest):
    import requests
    session = requests.Session()
    # First request to get confirmation token
    response = session.get(url, stream=True)
    token = None
    for key, value in response.cookies.items():
        if key.startswith('download_warning'):
            token = value
            break
    # If confirmation needed, re-request with token
    if token:
        response = session.get(url + '&confirm=' + token, stream=True)
    # Check if we got HTML instead of binary (means download failed)
    content_type = response.headers.get('Content-Type', '')
    if 'text/html' in content_type:
        # Try gdown-style URL
        file_id = url.split('id=')[-1]
        response = session.get(
            f'https://drive.usercontent.google.com/download?id={file_id}&export=download&confirm=t',
            stream=True
        )
    with open(dest, 'wb') as f:
        for chunk in response.iter_content(32768):
            if chunk:
                f.write(chunk)
    # Verify it's a valid h5 file
    if os.path.getsize(dest) < 1000:
        os.remove(dest)
        raise ValueError('Downloaded file is too small — likely an HTML error page, not the model.')

def get_gradcam(m, img_array):
    last_conv = None
    for layer in reversed(m.layers):
        if isinstance(layer, tf.keras.layers.Conv2D):
            last_conv = layer.name
            break
    if last_conv is None:
        return None

    try:
        grad_model = tf.keras.models.Model(
            inputs=m.inputs,
            outputs=[m.get_layer(last_conv).output, m.outputs[0]]
        )

        img_tensor = tf.cast(img_array, tf.float32)
        with tf.GradientTape() as tape:
            tape.watch(img_tensor)
            conv_output, predictions = grad_model(img_tensor)
            loss = predictions[:, 0]

        grads = tape.gradient(loss, conv_output)
        pooled_grads = tf.reduce_mean(grads, axis=(0, 1, 2))
        conv_output = conv_output[0]
        heatmap = conv_output @ pooled_grads[..., tf.newaxis]
        heatmap = tf.squeeze(heatmap)
        heatmap = tf.maximum(heatmap, 0) / (tf.math.reduce_max(heatmap) + 1e-8)
        return heatmap.numpy()
    except Exception:
        return None

@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    try:
        m = load_model()
    except FileNotFoundError as e:
        return JSONResponse({"error": str(e)}, status_code=503)

    contents = await file.read()
    nparr = np.frombuffer(contents, np.uint8)
    image = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

    original = cv2.resize(image, (224, 224))
    img = original / 255.0
    img_array = np.expand_dims(img, axis=0).astype(np.float32)

    prediction = float(m.predict(img_array, verbose=0)[0][0])

    heatmap_b64 = None
    heatmap = get_gradcam(m, img_array)
    if heatmap is not None:
        heatmap_resized = cv2.resize(heatmap, (224, 224))
        heatmap_color = cv2.applyColorMap(np.uint8(255 * heatmap_resized), cv2.COLORMAP_JET)
        overlay = cv2.addWeighted(original.astype(np.uint8), 0.6, heatmap_color, 0.4, 0)
        _, buffer = cv2.imencode('.jpg', overlay)
        heatmap_b64 = base64.b64encode(buffer).decode('utf-8')

    return JSONResponse({"prediction": prediction, "heatmap": heatmap_b64})

@app.get("/health")
def health():
    model_exists = os.path.exists(MODEL_PATH)
    return {"status": "ok", "model_loaded": model_exists, "model_path": MODEL_PATH}
