<?php


// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SoapControllerTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_wsdl_is_available(): void
    {
        $response = $this->get(route('soap-wsdl'));

        $content = $this->getContentFromFile('tests/Managers/stubs/wsdl.xml');

        $response->assertOk();
        $this->assertSame($content, $response->getContent());
    }
}
