import logging
from random import randint

from flask import Flask, jsonify, request

app = Flask(__name__)
logger = logging.getLogger(__name__)
logger.setLevel(logging.INFO)

@app.route('/')
def index():
    return jsonify({'message': 'otel-sdk-examples-python-sdk'})


@app.route('/api/reqheaders')
def headers():
    logger.info("Request headers: %s", dict(request.headers))
    return jsonify({'headers': dict(request.headers)})


@app.route("/rolldice")
def roll_dice():
    player = request.args.get('player', default=None, type=str)
    result = str(roll())
    if player:
        logger.info("%s is rolling the dice: %s", player, result)
    else:
        logger.info("Anonymous player is rolling the dice: %s", result)
    return jsonify({'outcome': result})


def roll():
    res = randint(1, 6)
    return res

