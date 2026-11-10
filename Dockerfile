FROM php:8.2-cli-alpine
WORKDIR /srv/app
COPY app/ /srv/app/
ENV ELNEMER_UPSTREAM_API=https://million.elnemer-ai.com
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/srv/app"]
