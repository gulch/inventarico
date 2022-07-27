#!/usr/bin/env bash

# run this script => bash convert-to-mozjpeg.sh <path to folder>

EXTENSIONS="jpg|jpeg"
DIRECTORY="."

function doMozJpeg {

	AWK=awk

	if [ -n "$1" ]; then

		ORIGINAL_DATA_PLAIN=`stat --format "%s %Y" "$1"`
	    ORIGINAL_PLAIN_SIZE=`echo "$ORIGINAL_DATA_PLAIN" | $AWK '{ print $1}'`

	    mozjpeg -quality "$QUALITY" -optimize -progressive "$1" > "$1--MOZ"

	    CONVERTED_DATA_PLAIN=`stat --format "%s %Y" "$1--MOZ"`
	    CONVERTED_PLAIN_SIZE=`echo "$CONVERTED_DATA_PLAIN" | $AWK '{ print $1}'`

	    if [ $CONVERTED_PLAIN_SIZE -eq 0 ]; then
	        echo -e "\e[31m❌ ERROR $1: optimized file size is 0. Rollback! \e[0m"
	        rm "$1--MOZ"
	        exit 0;
	    fi

	    PERCENT_SAVED=`$AWK -v var1=$CONVERTED_PLAIN_SIZE -v var2=$ORIGINAL_PLAIN_SIZE 'BEGIN { printf "%.2f", ( (1 - var1 / var2) * 100 ) }'`

	    if (( $(echo "$PERCENT_SAVED < 0" |bc -l) )); then
	        echo -e "\e[31m❌ NEGATIVE OPTIMIZATION $1: percent is $PERCENT_SAVED%. Rollback! \e[0m"
	        rm "$1--MOZ"
	        exit 0;
	    fi

	    mv "$1--MOZ" "$1"

	    echo -e "✔️Optimized: $1 from $ORIGINAL_PLAIN_SIZE to $CONVERTED_PLAIN_SIZE and save $PERCENT_SAVED%"
	fi
}

if [ -n "$1" ]; then
    DIRECTORY="$1"
fi

export -f doMozJpeg
find $DIRECTORY -type f -regextype posix-egrep -regex ".*\.($EXTENSIONS)\$" -exec bash -c 'doMozJpeg "{}"' \;