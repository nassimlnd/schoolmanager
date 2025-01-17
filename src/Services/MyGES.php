<?php

namespace App\Services;

use GuzzleHttp\Client as HTTPClient;
use GuzzleHttp\Exception\GuzzleException;

class MyGES
{
    private KordisClient $client;

    const SERVICES_URL = 'https://api.kordis.fr';

    const GET_PROFILE_ENDPOINT  = '/profile';
    const GET_AGENDA_ENDPOINT   = '/agenda';
    const GET_NEWS_ENDPOINT     = '/news';
    const GET_NEWS_BANNERS_ENDPOINT = '/news/banners';
    const GET_GRADES_ENDPOINT   = '/{year}/grades';
    const GET_ABSENCES_ENDPOINT = '/{year}/absences';
    const GET_CLASSES_ENDPOINT  = '/{year}/classes';
    const GET_STUDENTS_ENDPOINT = '/classes/{classeId}/students';
    const GET_STUDENT_ENDPOINT  = '/students/{studentId}';
    const GET_TEACHERS_ENDPOINT = '/{year}/teachers';
    const GET_TEACHER_ENDPOINT  = '/teachers/{teacherId}';
    const GET_COURSES_ENDPOINT  = '/{year}/courses';
    const GET_COURSE_ENDPOINT   = '/courses/{courseId}';
    const GET_PROJECTS_ENDPOINT = '/{year}/projects';
    const GET_PRACTICALS_ENDPOINT = '/{year}/practicals';
    const GET_COURSE_DOCUMENT_ENDPOINT  = '/{courseId}/files';

    public function __construct(KordisClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get current profile
     *
     * @return object|null
     * @throws GuzzleException
     */
    public function getProfile() : ?object
    {
        $url = $this->getUrl(self::GET_PROFILE_ENDPOINT);

        $client = new HTTPClient();

        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ]
        ]);

        return json_decode($response->getBody()->getContents())->result;
    }

    /**
     * Update current profile
     *
     * @param array $fields
     * @return array|null
     * @throws GuzzleException
     */
    public function updateProfile(array $fields) : ?array
    {
        $data = $this->getProfile();

        foreach ($fields as $field => $value) {
            $data->{$field} = $value;
        }

        $url = $this->getUrl(self::GET_PROFILE_ENDPOINT);

        $client = new HTTPClient();
        $response = $client->request('PUT', $url, [
            'headers' => [
                'Accept' => '*/*',
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ],
            'json' => $data
        ]);

        return json_decode($response->getBody()->getContents())->result;
    }

    /**
     * Get agenda between two dates
     *
     * @param string $startAt
     * @param string $endedAt
     * @return array|null
     * @throws GuzzleException
     * @todo determine startAt and endedAt format
     */
    public function getAgenda(string $startAt, string $endedAt) : ?array
    {
        $url = $this->getUrl(self::GET_AGENDA_ENDPOINT);

        $client = new HTTPClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ],
            'query' => [
                'end' => $endedAt,
                'start' => $startAt
            ]
        ]);

        return json_decode($response->getBody())->result;
    }

    /**
     * Get news with pagination
     *
     * @param integer $page
     * @return array|null
     * @throws GuzzleException
     */
    public function getNews(int $page = 0) : ?array
    {
        $url = $this->getUrl(self::GET_NEWS_ENDPOINT);

        $client = new HTTPClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ],
            'query' => [
                'page' => $page
            ]
        ]);

        return json_decode($response->getBody())->result;
    }

    /**
     * Get news banners
     *
     * @return array|null
     * @throws GuzzleException
     */
    public function getNewsBanners() : ?array
    {
        $url = $this->getUrl(self::GET_NEWS_BANNERS_ENDPOINT);

        $client = new HTTPClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ]
        ]);

        return json_decode($response->getBody())->result->content;
    }

    /**
     * Get grades / year
     *
     * @param integer $year
     * @return array|null
     * @throws GuzzleException
     */
    public function getGrades(int $year) : ?array
    {
        $url = $this->getUrl(self::GET_GRADES_ENDPOINT);
        $url = str_replace('{year}', $year, $url);

        $client = new HTTPClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ]
        ]);

        return json_decode($response->getBody())->result;
    }

    /**
     * Get absences / year
     *
     * @param integer $year
     * @return array|null
     * @throws GuzzleException
     */
    public function getAbsences(int $year) : ?array
    {
        $url = $this->getUrl(self::GET_ABSENCES_ENDPOINT);
        $url = str_replace('{year}', $year, $url);

        $client = new HTTPClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ]
        ]);

        return json_decode($response->getBody())->result;
    }

    /**
     * Get classes / year
     *
     * @param integer $year
     * @return array
     * @throws GuzzleException
     */
    public function getClasses(int $year): array
    {
        $url = $this->getUrl(self::GET_CLASSES_ENDPOINT);
        $url = str_replace('{year}', $year, $url);

        $client = new HTTPClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ]
        ]);

        return json_decode($response->getBody())->result;
    }

    /**
     * Get student by studentId
     *
     * @param integer $studentId
     * @return ?Object
     * @throws GuzzleException
     */
    public function getStudent(int $studentId) : ?Object
    {
        $url = $this->getUrl(self::GET_STUDENT_ENDPOINT);
        $url = str_replace('{studentId}', $studentId, $url);

        $client = new HTTPClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ]
        ]);

        return json_decode($response->getBody())->result;
    }

    /**
     * Get students by classeId
     *
     * @param integer $classeId
     * @return array
     * @throws GuzzleException
     */
    public function getStudents(int $classeId) : array
    {
        $url = $this->getUrl(self::GET_STUDENTS_ENDPOINT);
        $url = str_replace('{classeId}', $classeId, $url);

        $client = new HttpClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ]
        ]);

        return json_decode($response->getBody())->result;
    }

    /**
     * Get teachers / year
     *
     * @param integer $year
     * @return array
     * @throws GuzzleException
     */
    public function getTeachers(int $year): array
    {
        $url = $this->getUrl(self::GET_TEACHERS_ENDPOINT);
        $url = str_replace('{year}', $year, $url);

        $client = new HTTPClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ]
        ]);

        return json_decode($response->getBody())->result;
    }

    /**
     * Get teacher by teacherId
     *
     * @param integer $teacherId
     * @return ?Object
     * @throws GuzzleException
     */
    public function getTeacher(int $teacherId) : ?Object
    {
        $url = $this->getUrl(self::GET_TEACHER_ENDPOINT);
        $url = str_replace('{teacherId}', $teacherId, $url);

        $client = new HTTPClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ]
        ]);

        return json_decode($response->getBody())->result;
    }

    /**
     * Get courses / year
     *
     * @param integer $year
     * @return array
     * @throws GuzzleException
     */
    public function getCourses(int $year): array
    {
        $url = $this->getUrl(self::GET_COURSES_ENDPOINT);
        $url = str_replace('{year}', $year, $url);

        $client = new HTTPClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ]
        ]);

        return json_decode($response->getBody())->result;
    }

    public function getCourseDocuments(int $courseId): array
    {
        $url = $this->getUrl(self::GET_COURSE_DOCUMENT_ENDPOINT);
        $url = str_replace('{courseId}', $courseId, $url);

        $client = new HTTPClient();
        $response = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->client->getAccessToken(),
            ]
        ]);

        return json_decode($response->getBody())->result;
    }

    /**
     * Return full url
     *
     * @param string $endpoint
     * @return string
     */
    private function getUrl(string $endpoint) : string
    {
        return self::SERVICES_URL . '/me' . $endpoint;
    }

    public function encodeCredentials($login, $password)
    {
        return base64_encode($login . ':' . $password);
    }

    public function decodeCredentials($credentials)
    {
        return base64_decode($credentials);
    }

}
