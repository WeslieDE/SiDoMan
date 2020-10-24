# SIDOMAN ( Simple Docker Manager)

SiDoMan ist a simple webinterface to manage a single docker container. Developed to give a stranger the ability to control a docker container without getting access to the host system.
Its provides a webpage to give the abillity to start/stop/kill/restart a single container without have access to the full system.
SiDoMan runs in a docker container and dont need any configuration. Its out the box runable.

# Installation

SiDoMan runs in one docker container. You can start it with this command:

> docker run -p 80:80 -v /var/run/docker.sock:/var/run/docker.sock sahrea/sidoman

# Add a Container to SiDoMan

SiDoMan works without an own database or datastorage. To enable a container for SiDoMan you just need add a label to it.

> docker run --label "remotepass=<password>"

After that you can open a browser and open http://127.0.0.1:80.
You must enter the container name and the password.

# API

SiDoMan offers a rest api to controll the container.
You can "start/stop/kill/restart/send a command/see the log/get the state" over a simple http request.

To start a container:
> http://127.0.0.1/api.php?CONTAINER=Proxy&KEY=123&METODE=START

To stop a container:
> http://127.0.0.1/api.php?CONTAINER=Proxy&KEY=123&METODE=STOP

To kill a container:
> http://127.0.0.1/api.php?CONTAINER=Proxy&KEY=123&METODE=KILL

To restart a container:
> http://127.0.0.1/api.php?CONTAINER=Proxy&KEY=123&METODE=RESTART

See the state of a container:
> http://127.0.0.1/api.php?CONTAINER=Proxy&KEY=123&METODE=STATE

Get the log of the container:
> http://127.0.0.1/api.php?CONTAINER=Proxy&KEY=123&METODE=LOG

Send a command to a detached container:
> http://127.0.0.1/api.php?CONTAINER=Proxy&KEY=123&METODE=COMMAND&COMMAND=help

