<?php
namespace SearchEngine;
use ArrayIterator;
use Exception;

Class DataHelper {

    public function curlData($keywords, $engine, $api) {
        $URL = $this->getCorrectDomain($engine, $keywords, $api);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    private function getCorrectDomain($engine, $keywords, $api_key): string
    {
        return $engine == "google.com" ? 
        'https://serpapi.com/search.json?engine=google&q='. urlencode($keywords) .'&hl=en&google_domain='. $engine .'&num=50&start=0&api_key='.$api_key 
        : 'https://serpapi.com/search.json?engine=google&q='. urlencode($keywords) .'&hl=en&gl=ae&google_domain='. $engine .'&num=50&start=0&api_key='.$api_key ;
    }

    /**
     * @throws Exception
     */
    public function filterData($data): ArrayIterator
    {
        $jsonResponse = json_decode($data);
        if(isset($jsonResponse->organic_results)) {
            return new ArrayIterator($jsonResponse->organic_results);
        }
        throw new Exception('No data found');
    }

    public function processingData($Data, $value): array
    {
        $arrayOfData = array();
            while($Data->valid()) {
                $anArray = array();
                $innerResponseArray = new ArrayIterator($Data->current());
                while($innerResponseArray->valid()) {
                    if (!is_object($innerResponseArray->current())) {
                        $anArray[$innerResponseArray->key()] = $innerResponseArray->current();
                        $anArray["keyword"] = $value;
                    }
                    $innerResponseArray->next();
                }
                $arrayOfData[] = $this->cleanData($anArray);
                $Data->next();
            }
            return $arrayOfData;
    }

    private function cleanData(array $anArray): array
    {
        $anArray['ranking'] = $anArray['position'];
        $anArray['url'] = $anArray['link'];
        $anArray['description'] = $anArray['snippet'];
        unset($anArray['snippet_highlighted_words']);
        unset($anArray['thumbnail']);
        unset($anArray['displayed_link']);
        unset($anArray['date']);
        unset($anArray['related_results']);
        unset($anArray['rich_snippet_table']);
        unset($anArray['position']);
        unset($anArray['link']);
        unset($anArray['snippet']);
        return $anArray;
    }
}