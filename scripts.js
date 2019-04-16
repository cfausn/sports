"use strict";

var urlList = [];
var theItems = [];
var favorites = [];
var isDisabled = [];
var topscroll = $(window).scrollTop();
var previous = "";

if(localStorage){
    $(document).ready(function(){
        if(localStorage.getItem("lastVisit")) {
            document.getElementById("lastVisit").innerHTML = localStorage.getItem("lastVisit");
        } else {
            document.getElementById("lastVisit").innerHTML = "";
        }
        localStorage.setItem("lastVisit", new Date().toLocaleString());
    });
} else{
    alert("Storage not supported.");
}


function logout() {
    var request = new XMLHttpRequest();
    request.open("POST", "login.php", true);
    //request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    return true;
}

function deleteElement(elem) {
    var index = -1;
    favorites.forEach(function(fav){
        if(new Date(fav.date).getTime() == new Date(elem.id).getTime()) {
            isDisabled.splice(isDisabled.indexOf(elem.id), 1);
            index = favorites.indexOf(fav);
        }
    });
    favorites.splice(index, 1);
    updateFavs();
}

function favorite(elem) {
    var line = '<button id="'+elem.id+'" onclick="favorite(this)">Favorite</button>';
    theItems.forEach(function(item){
        if(item.date.getTime() == new Date(elem.id).getTime()) {
            isDisabled.push(elem.id);
            item.htmlLine = item.htmlLine.replace(line,'<button id="'+elem.id+'" onclick="deleteElement(this)">Remove</button>');
            favorites.push(item);
        }
    });
    updateFavs();
}

function updateFavs() {
    var html = "";

    favorites.sort(function(a,b){
        return b.date - a.date;
    });

    if (favorites.length == 0) {
        html = "Selected favorites will display here"
    } else {
        favorites.forEach(function(fav){
            html += fav.htmlLine;
        });
    }

    document.querySelector("#favorites").innerHTML = html;
    topscroll = $(window).scrollTop();

    init(urlList);
}

function check(elem) {
    topscroll = $(window).scrollTop();
    var url = "http://www.espn.com/espn/rss/" + elem.id + "/news";
    if(elem.checked) {
        urlList.push(url);
    }
    else {
        var index = urlList.indexOf(url);
        if(index > -1) {
            urlList.splice(index, 1);
        }
    }
    init(urlList);
}


window.onload = function(){
    getData();
}


function init(urlList){
    if(urlList.length > 0) {
        xmlLoaded(urlList);
    } else {
        document.querySelector("#sportsContent").innerHTML = "<p>Nothing loaded!</p>";
        previous="";
    }

}
function xmlLoaded(urlList){
    document.querySelector("#sportsContent").innerHTML = previous;
    var sportItems = [];
    var count = 0;

    urlList.forEach(function(url){
        $.get(url).done(function(obj){
            document.querySelector("#sportsContent").innerHTML = "";
            var theItems = obj.querySelectorAll("item");

            for (var i=0;i<theItems.length;i++){

                var newsItem = theItems[i];

                var title = newsItem.querySelector("title").firstChild.nodeValue;
                var description = newsItem.querySelector("description").firstChild.nodeValue;
                var link = newsItem.querySelector("link").firstChild.nodeValue;
                var pubDate = newsItem.querySelector("pubDate").firstChild.nodeValue;

                var line = '';
                if (url.indexOf('mlb') !== -1) { line = '<div class="item" id="mlb">'; }
                else if (url.indexOf('nhl') !== -1) { line = '<div class="item" id="nhl">'; }
                else if (url.indexOf('nfl') !== -1) { line = '<div class="item" id="nfl">'; }
                line += "<h2>"+title+"</h2>";
                line += '<p><i>'+pubDate+'</i> - <a href="'+link+'" target="_blank">See original</a></p>';

                var isEnabled = false;
                isDisabled.forEach(function(date){
                    if(new Date(pubDate).getTime() == new Date(date).getTime()) {
                        line += '<button id="'+pubDate+'" onclick="favorite(this)" isDisabled>Story is a favorite.</button>';
                        isEnabled = true;
                    }
                });
                if(!isEnabled){
                    line += '<button id="'+pubDate+'" onclick="favorite(this)">Favorite</button>';
                }

                line += "</div>";
                sportItems.push({ htmlLine: line, date: new Date(pubDate) });
            }
            count++;
            _callback(sportItems, count);
        });
    });
}

function _callback(sportItems, count) {
    if (count == urlList.length) {
        sportItems.sort(function(a,b){
            return b.date - a.date;
        });

        theItems = sportItems;

        var html = "";

        sportItems.forEach(function(item){
            html += item.htmlLine;
        });

        document.querySelector("#sportsContent").innerHTML += html;
        previous = html;
        $(window).scrollTop(topscroll);
    }
}
