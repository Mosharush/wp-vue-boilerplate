#!/bin/bash

# Check if openssl installed
if [ -z $(which openssl) ]; then
    brew install openssl || apt-get install openssl
fi

# Create a Diffie-Hellman Key Pair
openssl dhparam -out ./certificates/dhparam.pem 128

#Generate private key and certificate signing request
openssl genrsa -des3 -passout pass:x -out ./certificates/rootCA.key 2048
openssl req -x509 -passin pass:x -new -nodes -key ./certificates/rootCA.key -sha256 -days 500 -out ./certificates/rootCA.pem -config <( cat server.csr.cnf )
openssl req -new -sha256 -nodes -out ./certificates/server.csr -newkey rsa:2048 -keyout ./certificates/server.key -config <( cat server.csr.cnf )
openssl req -x509 -sha256 -nodes -days 500 -newkey rsa:2048 -keyout ./certificates/server.key -out ./certificates/server.crt -config <( cat server.csr.cnf )

# Generate SSL certificate
openssl x509 -req -passin pass:x -in ./certificates/server.csr -CA ./certificates/rootCA.pem -CAkey ./certificates/rootCA.key -CAcreateserial -out ./certificates/server.crt -days 500 -sha256 -extfile v3.ext

rm -f ./certificates/rootCA.key

echo 'SSL key was generated successfully!'