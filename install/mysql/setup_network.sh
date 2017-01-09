#!/bin/bash

# sciomino api
# zet het timebox netwerk op
mysql -u sciomino -ptimebox sciomino_sciomino_api_sciomino20 < data/network.sql

# timebox api
# zet het timebox netwerk op
mysql -u sciomino -ptimebox sciomino_timebox_api < data/timebox.sql

