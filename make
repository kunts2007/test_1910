#!/bin/sh

if [ -n "$1" ]
then
	uid=$(id -u ${USER});
	gid=$(id -g ${USER});

	export DOCKER_UID=$uid;
	export DOCKER_GID=$gid;

	case $1 in
		build)
		    docker-compose build;
			;;
		up)
			docker-compose down --remove-orphans;
			docker stop 12345 $(docker ps -q);
			docker-compose up -d --build;
			docker-compose exec --user $uid:$gid php composer install
			;;
		down)
			docker-compose down --remove-orphans;
			;;
		clear)
			docker-compose down -v --remove-orphans
			;;
			
		pull)
			docker-compose pull;
			;;

		web)
			docker-compose exec --user $uid:$gid web sh
			;;
		php)
			docker-compose exec --user $uid:$gid php sh
			;;
		sql)
			docker-compose exec db psql --username=test
			;;
		dump)
			docker-compose exec -T db mysql -uroot -proot online < ./dumps/online.sql
			;;
	esac
fi
