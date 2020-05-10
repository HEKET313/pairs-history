FROM alpine:3.6

ARG proxy
ARG sproxy
ARG noproxy

ENV http_proxy="$proxy"
ENV HTTP_PROXY="$proxy"
ENV https_proxy="$sproxy"
ENV HTTPS_PROXY="$sproxy"

MAINTAINER Vincent Composieux <vincent.composieux@gmail.com>

RUN apk add --update nginx
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

ADD nginx.conf /etc/nginx/
ADD symfony.conf /etc/nginx/conf.d/

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN adduser -D -g '' -G www-data www-data

CMD ["nginx"]

EXPOSE 81
EXPOSE 443
