Diagrams Service
================

The Diagrams Service is a small PHP web service
for rendering Graphviz, MSCGen, and PlantUML diagrams.

[![CI badge](https://github.com/samwilson/diagrams-service/workflows/CI/badge.svg)](https://github.com/samwilson/diagrams-service/actions?query=workflow%3ACI)

## Installation

1. Clone the code to a web-accessible location: `git clone https://github.com/samwilson/diagrams-service.git`
2. Go into the new directory: `cd diagrams-service`
3. Install dependencies: `composer install --no-dev -o`
4. Go to the `diagrams-service/public/` directory in your web browser

## Usage

### Web usage

1. Navigate to the web interface
2. Enter your diagram's source in the textarea
3. Select rendering engine and any other parameters
4. Click 'Render'

### API usage

@TODO

## License: MIT

Copyright 2019 Sam Wilson.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software
and associated documentation files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or
substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

