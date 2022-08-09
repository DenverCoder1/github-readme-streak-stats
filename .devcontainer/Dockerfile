FROM mcr.microsoft.com/vscode/devcontainers/base:ubuntu-22.04

ADD first-run-notice.txt /usr/local/etc/vscode-dev-containers/first-run-notice.txt

RUN apt-get update -y && \
    apt-get install -y php php-curl php-xml inkscape composer
