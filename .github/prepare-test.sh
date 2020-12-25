#!/bin/bash

__DIR__=$(cd `dirname $0`; pwd)

cd $__DIR__

containerName=$1

docker-compose up -d $containerName \
&& docker exec $containerName php -v \
&& docker exec $containerName php -m \
&& docker exec $containerName composer -V \
&& docker ps -a

n=0
until [ $n -ge 5 ]
do
  docker exec $containerName composer update && break
  n=$[$n+1]
done
