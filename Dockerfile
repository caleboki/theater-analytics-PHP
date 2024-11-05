FROM php:8.2-cli

WORKDIR /app

# Install SQLite and PHP SQLite extension
RUN apt-get update && \
    apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY . .

VOLUME ["/app/data"]

CMD ["php", "src/index.php"]