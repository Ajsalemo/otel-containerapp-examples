import logging
from instrumentation import initialize_instrumentation, initialize_otel_logging
from random import randint

from flask import Flask, jsonify, request
from opentelemetry import trace
from opentelemetry.instrumentation.flask import FlaskInstrumentor
# Manually initialize instrumentation
initialize_instrumentation()
# Initialize logging
app_logger = initialize_otel_logging()    

# Acquire a tracer
tracer = trace.get_tracer("server.tracer")

instrumentor = FlaskInstrumentor()  
app = Flask(__name__)
instrumentor.instrument_app(app)


@app.route('/')
def index():
    return jsonify({'message': 'otel-sdk-examples-python-sdk'})


@app.route('/api/reqheaders')
def headers():
    app_logger.info("Request headers: %s", dict(request.headers))
    return jsonify({'headers': dict(request.headers)})


@app.route("/rolldice")
def roll_dice():
    player = request.args.get('player', default=None, type=str)
    result = str(roll())
    if player:
        app_logger.info("%s is rolling the dice: %s", player, result)
    else:
        app_logger.info("Anonymous player is rolling the dice: %s", result)
    return jsonify({'outcome': result})


def roll():
    # This creates a new span that's the child of the current one
    with tracer.start_as_current_span("roll") as rollspan:
        res = randint(1, 6)
        rollspan.set_attribute("roll.value", res)
        return res

