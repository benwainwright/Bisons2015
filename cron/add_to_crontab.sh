#!/bin/bash
(crontab -l; echo "$1" ) | crontab -;
