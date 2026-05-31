FROM mcr.microsoft.com/vscode/devcontainers/base:ubuntu-24.04

# Add metadata
LABEL maintainer="your-email@example.com"
LABEL description="PHP development container with Inkscape"

# Copy configuration files
COPY first-run-notice.txt /usr/local/etc/vscode-dev-containers/first-run-notice.txt

# Install dependencies in a single layer with cleanup
RUN apt-get update -y && \
    apt-get install -y --no-install-recommends \
        php \
        php-curl \
        php-xml \
        php-mbstring \
        php-zip \
        inkscape \
        composer \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /workspace

# Verify installations
RUN php --version && \
    composer --version && \
    inkscape --version
