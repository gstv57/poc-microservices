<?php

describe('it should test any methods in the JobController', function () {

    it('should return a list of jobs', function () {
        $response = $this->get('/api/jobs');
        $response->assertStatus(200);
    });

    it('should return more details one job', function () {
        $response = $this->get('/api/jobs/1');
        $response->assertStatus(200);
    });
});
