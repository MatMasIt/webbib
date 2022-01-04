<?php

/**
 * Contains errors to be set as an api error response
 * 
 * This class contains a list of errors to be set as an API response, 
 *  specified as `QError:ERROR` which are:
 *  * `NOT_FOUND` : The specified resource was not found"
 *  * `UNAUTHORIZED` : You are not authorized to perform this operation
 *  * `OLDER_LOCAL` : Your local object is older than the remote one
 */
abstract class QError
{
    /**
     * The specified resource was not found
     */
    const NOT_FOUND = ["http" => 404, "code" => "NOT_FOUND", "description" => "The specified resource was not found"];
    /**
     * You are not authorized to perform this operatio
     */
    const UNAUTHORIZED = ["http" => 401, "code" => "UNAUTHORIZED", "description" => "You are not authorized to perform this operation"];
    /**
     * Your local object is older than the remote one
     */
    const OLDER_LOCAL = ["http" => 401, "code" => "OLDER_LOCAL", "description" => "Your local object is older than the remote one"];
}
/**
 * Api result and associated actions
 * 
 * This class stores and API result and its delivery
 */
class ApiResult
{

    private $data;
    private $error;
    public function __construct()
    {
    }
    /**
     * Set response data
     *
     * Set the Api result data to be sent
     *
     * @param array $data Result data
     *
     * @return void
     */
    public function data(array $data): void
    {
        $this->data = $data;
    }
    /**
     * Set response error
     *
     * Set the Api result errror to be sent
     *
     * @param array $e Result error
     *
     * @return void
     */
    public function error(array $e): void
    {
        $this->data = false;
        $this->error = $e;
    }
    /**
     * Send response
     *
     * Send api response in `json` format
     *
     * @param array $e Result error
     *
     * @return void
     */
    public function send(): void
    {
        if ($this->data) {
            http_response_code(200);
            $final = [
                "ok" => true,
                "data" => $this->data
            ];
        } else {
            http_response_code($this->erro["http"]);
            $final = [
                "ok" => false,
                "error" => [
                    "code" => $this->error["code"],
                    "description" => $this->error["description"]
                ]
            ];
        }
        $json = json_encode($final, JSON_PRETTY_PRINT);
        header("Content-Type: application/json");
        header("Content-Length: " . strlen($json));
        header("X-Webbib-Api-Ver: 1.0");
        echo $json;
        exit();
    }
}
