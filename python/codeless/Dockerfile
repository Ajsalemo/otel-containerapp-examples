FROM python:3.15.0b2-slim-bookworm

WORKDIR /app/
COPY requirements.txt /app/
RUN pip install -r requirements.txt

COPY . .

EXPOSE 8000

ENTRYPOINT [ "/app/entrypoint.sh" ] 