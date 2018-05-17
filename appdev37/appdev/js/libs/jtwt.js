//jtwt.js by Harbor (http://jtwt.hrbor.com)

(function($){

    $.fn.extend({

        //pass the options variable to the function
        jtwt: function(options) {


            //Set the default values, use comma to separate the settings, example:
            var defaults = {
                username : 'envato',
                count : 4,
                image_size: 48,
                convert_links: 1,
                loader_text: 'loading new tweets'
            }

            var options =  $.extend(defaults, options);

            return this.each(function() {
                var o = options;
                var obj = $(this);

                $(obj).append('<div id="jtwt_loader" style="display:none;">' + o.loader_text + '</div>');
                $("#jtwt_loader").fadeIn('slow');
                $(obj).append('<ul id="jtwt"></ul>');


                // Modified version of the prettyDate() function. Thanks to http://webdesign.onyou.ch/2010/08/04/javascript-time-ago-pretty-date/

                function prettyDate(date_str){

                    date_str = date_str.replace(/\+[0-9]{4}/, "");
                    date_str = date_str.substr(4);

                    var month = new Array();
                    month["Jan"] = "1";
                    month["Feb"] = "2";
                    month["Mar"] = "3";
                    month["Apr"] = "4";
                    month["May"] = "5";
                    month["Jun"] = "6";
                    month["Jul"] = "7";
                    month["Aug"] = "8";
                    month["Sep"] = "9";
                    month["Oct"] = "10";
                    month["Nov"] = "11";
                    month["Dec"] = "12";

                    var date_str_split = date_str.split(" ");
                    date_str = date_str_split[4];

                    date_str = date_str_split[4] + "-" + month[date_str_split[0]] + "-" + date_str_split[1] + "T" + date_str_split[2] + "Z";

                    var time_formats = [
                        [120, '1 minute ago', '1 minute from now'], // 60*2
                        [3600, 'minutes', 60], // 60*60, 60
                        [7200, '1 hour ago', '1 hour from now'], // 60*60*2
                        [86400, 'hours', 3600], // 60*60*24, 60*60
                        [604800, 'days', 86400], // 60*60*24*7, 60*60*24
                        [2419200, 'weeks', 604800], // 60*60*24*7*4, 60*60*24*7
                        [29030400, 'months', 2419200], // 60*60*24*7*4*12, 60*60*24*7*4
                        [58060800, 'last year', 'next year'], // 60*60*24*7*4*12*2
                        [2903040000, 'years', 29030400], // 60*60*24*7*4*12*100, 60*60*24*7*4*12
                        [5806080000, 'last century', 'next century'], // 60*60*24*7*4*12*100*2
                        [58060800000, 'centuries', 2903040000] // 60*60*24*7*4*12*100*20, 60*60*24*7*4*12*100
                    ];
                    var time = ('' + date_str).replace(/-/g,"/").replace(/[TZ]/g," ").replace(/^\s\s*/, '').replace(/\s\s*$/, '');
                    if(time.substr(time.length-4,1)==".") time =time.substr(0,time.length-4);
                    var seconds = (new Date - new Date(time)) / 1000;
                    var token = 'ago', list_choice = 1;
                    if (seconds < 0) {
                        seconds = Math.abs(seconds);
                        token = 'from now';
                        list_choice = 2;
                    }
                    var i = 0, format;
                    while (format = time_formats[i++])
                        if (seconds < format[0]) {
                            if (typeof format[2] == 'string')
                                return format[list_choice];
                            else
                                return Math.floor(seconds / format[2]) + ' ' + format[1] + ' ' + token;
                        }
                    return time;
                };

                $.getJSON('http://api.twitter.com/1/statuses/user_timeline/' + o.username + '.json?count=' + o.count + '&include_rts=true&callback=?', function(data){

                    $.each(data, function(i, item) {

                        jtweet = '<li class="jtwt_tweet clearfix">';



                        if (o.image_size != 0) {

                            //today = new Date();

                            jtweet += '<div class="jtwt_picture">';
                            jtweet += '<a href="http://twitter.com/' + item.user['screen_name'] + '">'
                            jtweet += '<img width="' + o.image_size +'" height="' + o.image_size + '" src="' + item.user['profile_image_url'] + '" />';
                            jtweet += '</a>';
                            jtweet += '</div>';

                        }



                        var tweettext = item.text;
                        var tweetdate = prettyDate(item.created_at);

                        if (o.convert_links != 0) {



                            tweettext = tweettext.replace(/(http\:\/\/[A-Za-z0-9\/\.\?\=\-]*)/g,'<a href="$1">$1</a>');
                            tweettext = tweettext.replace(/@([A-Za-z0-9\/_]*)/g,'<a href="http://twitter.com/$1">@$1</a>');
                            tweettext = tweettext.replace(/#([A-Za-z0-9\/\.]*)/g,'<a href="http://twitter.com/search?q=$1">#$1</a>');

                        }


                        jtweet += '<p class="jtwt_tweet_text">';
                        jtweet += tweettext;
                        jtweet += '</p>';

                        jtweet += '<a href="http://twitter.com/' + item.user['screen_name'] + '/statuses/' + item.id + '" class="jtwt_date">';

                        jtweet += tweetdate;
                        jtweet += '</a>';

                        jtweet += '</li>';

                        $("#jtwt", obj).append(jtweet);




                    });


                    $("#jtwt_loader").fadeOut('fast');

                });



            });
        }
    });

})(jQuery);