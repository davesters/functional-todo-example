# Functional PHP Todo Example

A todo application written in PHP in a functional style

## Dependencies

The app uses Docker to run. The following dependencies are required:

* Docker
* Docker Compose
* Composer

## Running the app

Once the dependencies are installed and you have cloned the repo to your machine, you can run composer and then launch the app:

    php ./composer.phar install
    docker-compose up
    
    // App will be available at http://localhost:8080

You will need to setup the database first, since I have not bothered to automate it upon launch. You can use the `database.sql` file to create the database and required table. Docker compose will bind port 3306 to your local machine, so you can use any MySql database client to access it at `localhost:3306'.

Docker compose will also mount the project folder into the container, so any changes you make will be updated in the container automatically. The normal development workflow is to leave the containers running while working on the app.

## Running the tests

To run the unit tests, run:

    ./test

This will run the tests within a docker container and then remove the container.

### License

This is free and unencumbered software released into the public domain.

Anyone is free to copy, modify, publish, use, compile, sell, or distribute this software, either in source code form or as a compiled binary, for any purpose, commercial or non-commercial, and by any means.

In jurisdictions that recognize copyright laws, the author or authors of this software dedicate any and all copyright interest in the software to the public domain. We make this dedication for the benefit of the public at large and to the detriment of our heirs and successors. We intend this dedication to be an overt act of relinquishment in perpetuity of all present and future rights to this software under copyright law.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

For more information, please refer to <http://unlicense.org/>