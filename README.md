# PHP test ülesanne

## Introduction

You are going to work with the Wikipedia search API. Wikipedia provides an endpoint to search articles for a given term. For instance this is the search result for the first
article with term "Tartu":
```json
 {
  "batchcomplete": "",
  "continue": {
    "sroffset": 1,
    "continue": "-||"
   },
  "query": {
    "searchinfo": {
      "totalhits": 4917
    },
    "search": [{
      "ns": 0,
      "title": "Tartu",
      "pageid": 31627,
      "size": 42974,
      "wordcount": 3554,
      "snippet": "<span class=\"searchmatch\">Tartu</span> (Estonian pronunciation: [<span class=\"searchmatch\">ˈtɑrtˑu</span>], So",
      "timestamp": "2018-03-09T17:23:41Z"
     }]
   }
}
```
Notice the timestamp field, which says when the article was last edited.

## Task``````
Your goal is to create a PHP program to retrieve all search results for term "Tartu" from Wikipedia and synchronize them into some local storage on a remote machine.
You will create two programs:
* a script that retrieves search results from Wikipedia and transmits them to the remote machine.
* an API which stores the search results locally; The API should expose the functionality: 
  * to save newly found articles
   * to update articles which have been changed on Wikipedia since the last synchronization (Look at the timestamp) 

It is up to you to decide whether to split those functionalities or to provide a single endpoint for them.   
On the API side you can store article descriptions in a simple json file or in SQLite or in whatever way you
want. Make sure to send as little data through the network as possible.

## Wikipedia API
The API endpoint to get search results is: https://en.wikipedia.org/w/api.php?action=query&format=json&list=search&utf8=1&srsearch=Tartu&srlimit=500&sroffset=0
Notice srlimit and sroffset query parameters. The request doesn't return all the entries at once but only returns first 500 results. To request next 500 results, change the
sroffset to 500, then to 1000, etc.

## Nice to consider
Any attempts to incorporate the following concepts into the solution are welcome:
Logging
Error handling
Testing
OOP
Modular programming
