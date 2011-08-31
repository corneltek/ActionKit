#!/bin/bash
export CFLAGS=" -march=prescott -O3 -msse -mmmx -funroll-loops -mfpmath=sse "
export CXXFLAGS="${CFLAGS}"
./configure --prefix=/home/c9s/local --with-apxs2=/usr/bin/apxs2 --disable-debug --enable-json

