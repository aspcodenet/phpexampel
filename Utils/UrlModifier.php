<?php
class UrlModifier{
    static function changeParameters($url, $parameters)
    {
        $urlParts = parse_url($url);


        $url = "";

        if (isset($urlParts['scheme'])) {
            $url .= $urlParts['scheme'] . "://";
        }

        if (isset($urlParts['host'])) {
            $url .= $urlParts['host'];
        }

        if (isset($urlParts['path'])) {
            $url .= $urlParts['path'];
        }

        $query = isset($urlParts['query']) ? $urlParts['query'] : "";

        $urlParameters = explode("&", $query);

        $urlParameterValuesByKey = new stdClass();

        foreach ($urlParameters as $urlParameter) {
            $equalsIndex = strrpos($urlParameter, "=");

            if ($equalsIndex) {
                $urlParameterValuesByKey->{substr($urlParameter, 0, $equalsIndex)} = substr($urlParameter, $equalsIndex + 1);
            } else {
                $urlParameterValuesByKey->{$urlParameter} = null;
            }
        }

        foreach ($parameters as $key => $value) {
            if(!is_string($value)) {
                unset($urlParameterValuesByKey->{$key});
            } else {
                $urlParameterValuesByKey->{$key} = $value;
            }
        }

        $query = "";

        foreach ($urlParameterValuesByKey as $key => $value) {
            if (strlen($query) === 0) {
                $query .= "?";
            }

            if (strlen($query) > 1) {
                $query .= "&";
            }
            if (is_string($value)) {
                $query .= $key . "=" . $value;
            } else {
                $query .= $key;
            }
        }


        $url .= $query;

        return $url;
}
};
?>