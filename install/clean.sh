#!/bin/bash

# remove backup readme
rm ../readme.html~

# clear logs & stuff
rm -f ../sciomino1.2/save/logs/log/*
cp /dev/null ../sciomino1.2/save/logs/xcow_base.log
rm -f ../sciomino1.2/save/sessions/*

rm -f ../sciomino2.0/save/logs/log/*
cp /dev/null ../sciomino2.0/save/logs/xcow_base.log
rm -f ../sciomino2.0/save/sessions/*

rm -f ../sciomino-answers/save/logs/log/*
cp /dev/null ../sciomino-answers/save/logs/xcow_base.log
rm -f ../sciomino-answers/save/sessions/*

rm -f ../sciomino-api/save/logs/log/*
cp /dev/null ../sciomino-api/save/logs/xcow_base.log
rm -f ../sciomino-api/save/sessions/*

rm -f ../sciomino-connect/save/logs/log/*
cp /dev/null ../sciomino-connect/save/logs/xcow_base.log
rm -f ../sciomino-connect/save/sessions/*

rm -f ../sciomino-graph/save/logs/log/*
cp /dev/null ../sciomino-graph/save/logs/xcow_base.log
rm -f ../sciomino-graph/save/sessions/*

rm -f ../timebox-api/save/logs/log/*
cp /dev/null ../timebox-api/save/logs/xcow_base.log
rm -f ../timebox-api/save/sessions/*
