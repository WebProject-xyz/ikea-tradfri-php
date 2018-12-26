FROM debian as builder
MAINTAINER Benjamin Fahl
ENV             DEBIAN_FRONTEND         noninteractive
ENV             DEBIAN_FRONTEND newt
RUN apt-get update \
    && apt-get install -y \
    make \
    automake \
    libtool \
    clang \
    gcc \
    git \
    libcunit1-dev \
    doxygen \
    libxml2-utils \
    xsltproc \
    docbook-xml \
    asciidoc
ENV             DEBIAN_FRONTEND newt
RUN git clone --depth 1 --recursive -b dtls https://github.com/home-assistant/libcoap.git \
    && cd libcoap \
    && ./autogen.sh \
    && ./configure --disable-documentation --disable-shared --without-debug CFLAGS="-D COAP_DEBUG_FD=stderr" \
    && make \
    && make install \
    && cd .. \
    && rm -rf libcoap
FROM debian:stable-slim as runner
COPY --from=builder ./usr/local/bin/coap-client /usr/local/bin/coap-client
COPY --from=builder ./usr/local/bin/coap-server /usr/local/bin/coap-server
COPY --from=builder ./usr/local/bin/coap-rd /usr/local/bin/coap-rd
COPY docker-entrypoint.sh /docker-entrypoint.sh
ENTRYPOINT ["/bin/sh", "/docker-entrypoint.sh"]
CMD ["/usr/local/bin/coap-client"]
