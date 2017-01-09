create database sciomino_sciomino12;
create database sciomino_sciomino20;
create database sciomino_sciomino_answers;
create database sciomino_sciomino_answers_sciomino12;
create database sciomino_sciomino_answers_sciomino20;
create database sciomino_sciomino_api;
create database sciomino_sciomino_api_sciomino12;
create database sciomino_sciomino_api_sciomino20;
create database sciomino_sciomino_connect;
create database sciomino_sciomino_graph;
create database sciomino_timebox_api;

grant all on sciomino_sciomino12.* to 'sciomino'@'localhost' identified by 'timebox';
grant all on sciomino_sciomino20.* to 'sciomino'@'localhost' identified by 'timebox';
grant all on sciomino_sciomino_answers.* to 'sciomino'@'localhost' identified by 'timebox';
grant all on sciomino_sciomino_answers_sciomino12.* to 'sciomino'@'localhost' identified by 'timebox';
grant all on sciomino_sciomino_answers_sciomino20.* to 'sciomino'@'localhost' identified by 'timebox';
grant all on sciomino_sciomino_api.* to 'sciomino'@'localhost' identified by 'timebox';
grant all on sciomino_sciomino_api_sciomino12.* to 'sciomino'@'localhost' identified by 'timebox';
grant all on sciomino_sciomino_api_sciomino20.* to 'sciomino'@'localhost' identified by 'timebox';
grant all on sciomino_sciomino_connect.* to 'sciomino'@'localhost' identified by 'timebox';
grant all on sciomino_sciomino_graph.* to 'sciomino'@'localhost' identified by 'timebox';
grant all on sciomino_timebox_api.* to 'sciomino'@'localhost' identified by 'timebox';

flush privileges;
