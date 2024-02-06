FROM python:3

WORKDIR /usr/src/app

RUN apt-get update
RUN apt-get install ffmpeg libsm6 libxext6  -y

RUN pip install werkzeug==2.1.2 opencv-python flask==2.1.2 flask_restx pillow
RUN pip install --pre torch torchvision -f https://download.pytorch.org/whl/nightly/cu117/torch_nightly.html
RUN pip install plydata plyfile

CMD [ "python", "api.py"]
