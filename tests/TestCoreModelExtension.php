<?php

use Orchestra\Testbench\TestCase;
use Dimsav\Translatable\Test\Model\Country;
use Dimsav\Translatable\Test\Model\CountryStrict;
use Dimsav\Translatable\Test\Model\CountryTranslation;

class TestCoreModelExtension extends TestsBase {

    // Failing saving

    /**
     * @expectedException Illuminate\Database\QueryException
     */
    public function testSaveTranslatableThrowsException()
    {
        $country = new Country();
        $country->name = 'Belgium';
        $country->save();
    }

    /**
     * @expectedException Illuminate\Database\QueryException
     */
    public function testSaveTranslationThrowsException()
    {
        $country = new Country();
        $country->iso = 'Belgium';
        $country->name = null;
        $country->save();
    }

    // Deleting

    public function testDeleting()
    {
        $country = Country::find(1);
        $countryId = $country->id;
        $translation = $country->en;
        $this->assertTrue(is_object($translation));
        $country->delete();
        $country = Country::find($countryId);
        $this->assertNull($country);

        $translations = CountryTranslation::where('country_id', '=', $countryId)->get();
        $this->assertEquals(0, count($translations));
    }

    public function testDeletingWithSoftDeleteDoesNotDeleteTranslations()
    {
        $country = CountryStrict::find(1);
        $before = CountryTranslation::where('country_id', '=', 1)->get();
        $country->delete();

        $after = CountryTranslation::where('country_id', '=', 1)->get();
        $this->assertEquals(count($before), count($after));

        $country->forceDelete();
        $after = CountryTranslation::where('country_id', '=', 1)->get();
        $this->assertEquals(0, count($after));
    }

} 