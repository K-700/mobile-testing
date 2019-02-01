#!/bin/bash

if [[ -z "$@" ]]
then
    echo "Usage:   ./start_tests.bash [OPTIONS]"
    echo "Simple run all acceptance tests: ./start_tests.bash --codeception \"run acceptance\""
    echo "Simple run all ios tests: ./start_tests.bash --codeception \"run ios\""
    echo "OPTIONS:"
    echo "-q                                   | quiet mode"
    echo "-t <secs>                            | how long to wait for reconnect"
    echo "--appium \"<appium_params>\"           | send params to appium server"
    echo "--codeception \"<codeception_params>\" | send params to codeception"
    exit 0
fi

# available keys
b_quiet_mode=0  # -q
i_seconds_wait=60 # -t secs

# params
s_appium_params=''
s_codeception_params=''
appium_port='4723'
appium_host='localhost'

# mobile or desktop test need to run
s_tests_type=''

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
        --codeception)
            s_codeception_params="$2"
            # get type of tests from codeception params line
            a_temp_codeception_params=(${s_codeception_params})
            for (( i=0; i<${#a_temp_codeception_params[@]}; i++ )); do
                case "${a_temp_codeception_params[$i]}" in
                    run)
                        s_tests_type="${a_temp_codeception_params[$((i+1))]}"
                        let "i++";;
                esac
            done
            shift;;
    esac
    shift
done

i_seconds=0
case "$s_tests_type" in
    ios)
        # wait until Appium is load
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
    ;;
    acceptance)
        ## Add chromedriver to PATH environment if it wasn't added
        [[ ":$PATH:" != *":$PWD/vendor/bin:"* ]] && PATH="$PWD/vendor/bin:${PATH}"
        # wait until Selenium is loaded
        until $(curl --output /dev/null --silent --head --fail http://localhost:4444/wd/hub); do
            if (($i_seconds == 0)); then
                # start Selenium
                if [[ "$b_quiet_mode" -ne 0 ]]; then
                    vendor/bin/selenium-server-standalone >/dev/null 2>&1 &
                else
                    vendor/bin/selenium-server-standalone &
                fi
            fi

            if (($i_seconds == $i_seconds_wait)); then
                [ "$b_quiet_mode" -ne 0 ] && exit 0
                read -r -p "Could not start or contact Selenium. Try to reconnect? [y/N] " s_response
                s_response=${s_response,,}    # tolower
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
    ;;
esac

# run tests
if [[ -z "$s_codeception_params" ]]; then
    php vendor/bin/codecept run
else
    php vendor/bin/codecept $s_codeception_params
fi