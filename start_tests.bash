#!/bin/bash

if [[ -z "$@" ]]
then
    echo "Usage:   ./start_tests [OPTIONS] test path/to/test1.php path/to/test2.php"
    echo "OR:      ./start_tests [OPTIONS] --tests path/to/tests/directory1/ path/to/tests/directory2/"
    echo "OR:      ./start_tests [OPTIONS] test path/to/test1.php path/to/test2.php --tests path/to/tests/directory1/ path/to/tests/directory2/"
    echo "OPTIONS:"
    echo "-q                           | quiet mode"
    echo "-t <secs>                    | how long to wait for reconnect"
    echo "--appium \"<appium_params>\"   | send params to appium server"
    echo "--phpunit \"<phpunit_params>\" | send params to phpunit"
    exit 0
fi

# available keys
b_quiet_mode=0  # -q
i_seconds_wait=60 # -t secs

# params
s_appium_params=''
s_phpunit_params=''
appium_port='4723'
appium_host='localhost'
a_test_paths=()

# check keys and params
while [ -n "$1" ]; do
    case "$1" in
        -q) b_quiet_mode=1;;
        -t) [[ -z "$2" || "$2" -lt 0 ]] && { echo "Invalid -t flag value"; exit 0; }
            i_seconds_wait="$2"
            shift;;
        --appium)
            s_appium_params="$2"
            # get host and port from appium params line
            a_temp_appium_params=(${s_appium_params})
            for (( i=0; i<${#a_temp_appium_params[@]}; i++ )); do
                case "${a_temp_appium_params[$i]}" in
                    --address|-a)
                        appium_host="${a_temp_appium_params[$((i+1))]}"
                        let "i++";;
                    --port|-p)
                        appium_port="${a_temp_appium_params[$((i+1))]}"
                        let "i++";;
                esac
            done
            shift;;
        --phpunit)
            s_phpunit_params="$2"
            shift;;
        test)
            # get all parameters until param with '-' at the beginning
            regex="^-.*"
            while [ ! -z "$2" ] && [[ ! $2 =~ $regex ]]; do
                a_test_paths+=("$2")
                shift
            done
            ;;
        --tests)
            # get all parameters until param with '-' at the beginning
            regex="^-.*"
            while [ ! -z "$2" ] && [[ ! $2 =~ $regex ]]; do
                # get all files in directories and push them into array
                s_test_directory="$2"
                for file in ${s_test_directory}*.php; do
                    a_test_paths+=("$file")
                done
                shift
            done
            ;;
    esac
    shift
done

# wait until Appium is load
i_seconds=0
until $(curl --output /dev/null --silent --head --fail http://"$appium_host":"$appium_port"/wd/hub/status); do
    if (($i_seconds == 0)); then
        # start Appium
        if [[ -z "$s_appium_params" ]]; then
            appium >/dev/null 2>&1 &
        else
            appium "$s_appium_params" >/dev/null 2>&1 &
        fi
    fi

    if (($i_seconds == $i_seconds_wait)); then
        [ "$b_quiet_mode" -ne 0 ] && exit 0
        read -r -p "Could not start or contact Appium. Try to reconnect? [y/N]" s_response
        s_response="$(tr '[:upper:]' '[:lower:]'<<<$s_response)"    # tolower
        if [[ "$s_response" =~ ^(yes|y)$ ]]; then
            i_seconds=0
        else
            exit 0
        fi
    else
        let "i_seconds++"
        sleep 1
    fi
done

# run desktop accpetance tests
for s_test_path in ${a_test_paths[*]}; do
    vendor/phpunit/phpunit/phpunit "$s_test_path" "$s_phpunit_params"
done