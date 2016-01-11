# PHP-Arduino-IoT
Simple PHP/MySQL based IoT plattform for Arduino clients and OpenWRT based SoCs.

This project is based on following thread: https://vpsboard.com/topic/8338-build-your-own-iot-plattform-arudino-with-shield-as-client

It will contain following parts:
- Arduino client
- SQL schema of MySQL databse
- PHP base Rest-Api

##Motivation
I really like Ruby - same with MongoDB, Redis, RabbitMQ and Hazelcast. But you have to configure and maintain the whole infrastructure for each single project.
Quite time consuming and sometimes frustrating if interfaces are changing or new config-files are invented. As fast as these frameworks and services are growing the more deprecated flags are spread through the users.
Not talking about failover, backup/restore and all the small things rising if more than 10 people are using a plattform.

A quad-core Raspberry Pi 2 running Ruby code, using a Phyton client connected with a RabbitMQ cluster to store information in a sharded MongoDB database. Whoohoo sounds good must be good. My math professor told me one thing that fits on such projects: "If you do not have a simple solution you are only fixing symptoms and not the real problem". He was and is right.

So the IoT thing is about reading analog/digital signals and storing them into a database. Exluding commands, workflows, visualization and all the other BI stuff.
I used the Raspberry Pi for most stuff because it was easy to run Ruby/Phyton scripts doing the whole work. But if you look to what has to be done there are three simple steps: measure, ship and store.
Measure signals is simple with any AVR 8-bit microcontroller. But they are lacking one thing: The internet connection.
The electronic things are quite easy and a physical LAN connection is easy but the TCP-IP stack is very expensive for microcontrollers - they have 2kb of RAM. Good luck to handle SSL handshakes.

But why should a single chip do all the work? The make-all-things-possible SoCs are quite expensive and you can only touch them through an SDK. No direct access to the core. So back to the basics.
What do you need to "ship" the data? Not much if you have an OpenWRT device. Curl is your friend. Just post the information and done. Authentification, SSL, encoding is done within this tool and you only need a bash and a working internet connection.
Most routers do not have a serial connection so I found an cheap way to connect the Arduino and the OpenWRT world.
You will find a quite simple solution on the server side too - if you look what has to be done - just receive a post request and store it into a database. Something, I strongly believe, that can be done without Java and Phyton.
We will of course build a REST-API, a documentation, authentification, tokens, SSL. So not a too simple PHP script - remeber we want to host the stuff on a shared hosting account for some $ per year.

##Links
Discussion and additional information can be found here: https://vpsboard.com/topic/8338-build-your-own-iot-plattform-arudino-with-shield-as-client

For more information visit:
  - wlanboy.com: http://www.wlanboy.com
  - vpsboard.comm: http://www.vpsboard.com
