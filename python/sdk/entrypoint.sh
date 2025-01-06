#!/bin/bash

gunicorn -b 0.0.0.0:8000 app:app --timeout 600 --access-logfile "-" --error-logfile "-" --log-level debug