# Dockerfile
#
# Generate a Docker container With StoryPlayer.
#
# Copyright (c) 2011-present Mediasift Ltd
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions
# are met:
#
#  # Redistributions of source code must retain the above copyright
#     notice, this list of conditions and the following disclaimer.
#
#  # Redistributions in binary form must reproduce the above copyright
#     notice, this list of conditions and the following disclaimer in
#     the documentation and/or other materials provided with the
#     distribution.
#
#  # Neither the names of the copyright holders nor the names of his
#     contributors may be used to endorse or promote products derived
#     from this software without specific prior written permission.
#
# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
# "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
# LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
# FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
# COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
# INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
# BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
# LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
# CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
# LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
# ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
# POSSIBILITY OF SUCH DAMAGE.
#
# @category  Libraries
# @package   Storyplayer
# @author    Nicola Asuni <nicola.asuni@datasift.com>
# @copyright 2011-present Mediasift Ltd www.datasift.com
# @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
# @link      http://datasift.github.io/storyplayer
# ------------------------------------------------------------------------------

# ------------------------------------------------------------------------------
# NOTES:
#
# This script requires Docker (https://www.docker.com/)
#
# Add your user to the "docker" group:
#     sudo groupadd docker
#     sudo gpasswd -a <YOURUSER> docker
#     sudo service docker.io restart
#
# To create the container execute:
#     docker build --tag="datasift/storyplayer:latest" .
#
# To log into the newly created container:
#     docker run -t -i datasift:storyplayer /bin/bash
#
# To get the container ID:
#     CONTAINER_ID=`docker ps -a | grep datasift:storyplayer | cut -c1-12`
#
# To delete the newly created docker container:
#     docker rm -f $CONTAINER_ID
#
# To delete the docker image:
#     docker rmi -f datasift:storyplayer
#
# To delete all docker images:
#     docker rmi -f `docker images -q`
# ------------------------------------------------------------------------------

FROM ubuntu:trusty
MAINTAINER nicola.asuni@datasift.com

ENV DEBIAN_FRONTEND noninteractive
ENV TERM linux
RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections

# configure ssh to disable strict host checking
RUN mkdir /root/.ssh
RUN echo "Host *" > /root/.ssh/config
RUN echo "    StrictHostKeyChecking no" >> /root/.ssh/config

RUN apt-get update && apt-get -y dist-upgrade
RUN apt-get -y install build-essential pkg-config openssh-server openssh-client wget curl rsync libzmq3-dev libzmq3 git screen ansible python php5 php5-dev php5-cli php5-common php5-curl php5-json php5-xsl php-pear libyaml-dev ruby-dev re2c
RUN yes '' | pecl install -f yaml-beta
RUN yes '' | pecl install -f zmq-beta
RUN echo "extension=yaml.so" > /etc/php5/cli/conf.d/20-yaml.ini
RUN echo "extension=zmq.so" > /etc/php5/cli/conf.d/20-zmq.ini
RUN echo "extension=yaml.so" > /etc/php5/apache2/conf.d/20-yaml.ini
RUN echo "extension=zmq.so" > /etc/php5/apache2/conf.d/20-zmq.ini

# install Vagrant
ENV VAGRANT_HOME /root/.vagrant.d
RUN cd /tmp && wget --no-check-certificate https://dl.bintray.com/mitchellh/vagrant/vagrant_1.7.4_x86_64.deb && dpkg -i vagrant_1.7.4_x86_64.deb && apt-get -y install -f
RUN vagrant plugin install vagrant-omnibus
RUN vagrant plugin install vagrant-openstack-plugin
RUN vagrant plugin install vagrant-aws

# install composer
RUN cd /var/www && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/bin/composer

# install StoryPlayer
ADD . /var/www/storyplayer
RUN cd /var/www/storyplayer && composer install
ENV PATH /var/www/storyplayer/src/bin:$PATH

# install the StoryPlayer Browser module
RUN cd /var/www/storyplayer && storyplayer install
