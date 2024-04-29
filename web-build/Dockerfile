#ddev-generated
# Note that the chromium-driver install could be moved to webimage_extra_packages
RUN sudo apt-get update && sudo apt-get install chromium-driver -y
# This will not be necessary in DDEV v1.23+
RUN corepack enable

# TODO:
# This section is temporary and needs to be removed when
# https://github.com/justafish/ddev-drupal-core-dev/pull/23/files#top
# is pulled, because it includes this update.
ARG TARGETPLATFORM
RUN SQLITE_VERSION=3.45.1 && \
    mkdir -p /tmp/sqlite3 && \
    wget -O /tmp/sqlite3/sqlite3.deb https://ftp.debian.org/debian/pool/main/s/sqlite3/sqlite3_${SQLITE_VERSION}-1_${TARGETPLATFORM##linux/}.deb && \
    wget -O /tmp/sqlite3/libsqlite3.deb https://ftp.debian.org/debian/pool/main/s/sqlite3/libsqlite3-0_${SQLITE_VERSION}-1_${TARGETPLATFORM##linux/}.deb && \
    dpkg -i /tmp/sqlite3/*.deb && \
    rm -rf /tmp/sqlite3
