FROM tensorflow/tensorflow:latest-gpu

WORKDIR /usr/src/app

RUN apt-get update
RUN apt-get install ffmpeg libsm6 libxext6  -y

RUN pip install werkzeug==2.1.2 opencv-python flask==2.1.2 flask_restx pillow

CMD [ "python", "api.py" ]
