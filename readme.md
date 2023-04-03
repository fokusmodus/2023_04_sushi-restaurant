# Sushi-Restaurant

## Getting started

1. Put the whole ``./sushie-restaurant`` folder inside an php environment.
2. Open the ``./sushie-restaurant/build`` folder in your browser through a server.

## Backend

The main file is ```controller.php```, that is be used as an entry point for several AJAX Requests. It uses JSON ``database.json`` for data to keep it simple.

### General considerations

1. if a perfect matching gap size appears, we use this one for an incoming equal sized group
2. if the incoming group is smaller, than existing gaps, we use the smallest of all bigger gaps to don't waste seats
3. seats can not be switched e.g. to close gaps

### Efficiency and technical considerations

1. to prevent endless looping, there is an additional array ``groupEmptyPosition`` that will be used to find empty groups faster
2. same applies for ``seatsEmpty`` variable to prevent possible starts of loops
3. one group equals one array key to keep arrays short (instead of seats)
4. empty groups, that are direct neighbours to each other, become consolidated to make the group array smaller



## Front-End Scripts

Just necessary for edits

run ``npm install``

run ```npm run css``` to start watcher for scss only

run ```npm run js``` to start watcher for js only

run ```npm run watch``` to start watcher for both running concurrently