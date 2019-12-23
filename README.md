Wiki Diagram Tool
=================

The Wiki Diagram Tool is a small PHP web service
for rendering Graphviz, MSCGen, and PlantUML diagrams
from 

![CI badge](https://github.com/samwilson/diagrams-service/workflows/CI/badge.svg)

## Installation

1. `git clone https://github.com/samwilson/wdt.git`
2. `cd wdt`
3. `composer install --no-dev -o`
4. Set up your webserver to serve the `public/` directory

## Usage

### Web usage

1. Navigate to the web interface
2. Enter your diagram's source in the textarea
3. Select rendering engine and any other parameters
4. Click 'Render'

### API usage

1. 

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

