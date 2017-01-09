#!/bin/bash

# sciomino api
mysql -u sciomino -ptimebox sciomino_sciomino_api < tables/sciomino_api.sql
# regel toegang voor frontends
mysql -u sciomino -ptimebox sciomino_sciomino_api < data/client.sql
mysql -u sciomino -ptimebox sciomino_sciomino_api_sciomino12 < tables/sciomino_api_client.sql
mysql -u sciomino -ptimebox sciomino_sciomino_api_sciomino20 < tables/sciomino_api_client.sql

# sciomino answers
mysql -u sciomino -ptimebox sciomino_sciomino_answers < tables/sciomino_answers.sql
# regel toegang voor frontends
mysql -u sciomino -ptimebox sciomino_sciomino_answers < data/client.sql
mysql -u sciomino -ptimebox sciomino_sciomino_answers_sciomino12 < tables/sciomino_answers_client.sql
mysql -u sciomino -ptimebox sciomino_sciomino_answers_sciomino20 < tables/sciomino_answers_client.sql

# sciomino connect
mysql -u sciomino -ptimebox sciomino_sciomino_connect < tables/sciomino_connect.sql

# timebox api
mysql -u sciomino -ptimebox sciomino_timebox_api < tables/timebox_api.sql

# frontends
mysql -u sciomino -ptimebox sciomino_sciomino12 < tables/sciomino12.sql
mysql -u sciomino -ptimebox sciomino_sciomino20 < tables/sciomino20.sql

# graph
mysql -u sciomino -ptimebox sciomino_sciomino_graph < tables/sciomino_graph.sql
# regel toegang tot data van frontends
mysql -u sciomino -ptimebox sciomino_sciomino_graph < data/graph.sql
