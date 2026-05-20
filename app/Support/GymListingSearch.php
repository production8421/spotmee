<?php

namespace App\Support;

use App\Models\GymListing;
use Illuminate\Database\Eloquent\Builder;

final class GymListingSearch
{
    /**
     * @return array<string, string> US state code => display name
     */
    public static function stateMap(): array
    {
        return (array) config('gym_listing.states', []);
    }

    /**
     * Resolve a search term to a two-letter state code (e.g. "Alabama" or "AL" → "AL").
     */
    public static function resolveStateCodeFromTerm(string $term): ?string
    {
        $term = trim($term);
        if ($term === '') {
            return null;
        }

        $states = self::stateMap();
        $upper = strtoupper($term);

        if (strlen($upper) === 2 && isset($states[$upper])) {
            return $upper;
        }

        foreach ($states as $code => $name) {
            if (strcasecmp($term, (string) $name) === 0) {
                return (string) $code;
            }
        }

        return null;
    }

    /**
     * True when the term is a two-letter state abbreviation (e.g. "AL", "ny").
     */
    public static function isTwoLetterStateAbbrev(string $term): bool
    {
        $upper = strtoupper(trim($term));

        return strlen($upper) === 2 && isset(self::stateMap()[$upper]);
    }

    /**
     * Redirect target when the user entered only a state abbreviation (not city/zip).
     */
    public static function redirectStateCodeIfAbbrev(string $searchBy, string $city): ?string
    {
        foreach ([$searchBy, $city] as $term) {
            if ($term !== '' && self::isTwoLetterStateAbbrev($term)) {
                return self::resolveStateCodeFromTerm($term);
            }
        }

        return null;
    }

    /**
     * Apply location search across city, zip/postal code, address, name, and state.
     *
     * @param  Builder<GymListing>  $query
     * @return string|null State code for UI when the search term is an exact full state name
     */
    public static function applyLocationFilters(Builder $query, string $searchBy, string $city): ?string
    {
        $searchBy = trim($searchBy);
        $city = trim($city);

        $uiState = null;

        if ($searchBy !== '') {
            self::applyTextSearch($query, $searchBy);
            if (self::isExactStateNameTerm($searchBy)) {
                $uiState = self::resolveStateCodeFromTerm($searchBy);
            }
        }

        if ($city !== '' && $city !== $searchBy) {
            self::applyTextSearch($query, $city);
            if ($uiState === null && self::isExactStateNameTerm($city)) {
                $uiState = self::resolveStateCodeFromTerm($city);
            }
        }

        return $uiState;
    }

    /**
     * Whether the term matches a full state name exactly (not a city sharing the name).
     */
    public static function isExactStateNameTerm(string $term): bool
    {
        $code = self::resolveStateCodeFromTerm($term);
        if ($code === null || self::isTwoLetterStateAbbrev($term)) {
            return false;
        }

        return strcasecmp(trim($term), (string) (self::stateMap()[$code] ?? '')) === 0;
    }

    /**
     * @param  Builder<GymListing>  $query
     */
    public static function applyTextSearch(Builder $query, string $searchBy): void
    {
        $searchBy = trim($searchBy);
        if ($searchBy === '') {
            return;
        }

        $like = '%'.addcslashes($searchBy, '%_\\').'%';
        $stateCode = self::resolveStateCodeFromTerm($searchBy);
        $zipLike = self::looksLikePostalCode($searchBy);

        $query->where(function ($sub) use ($like, $stateCode, $searchBy, $zipLike): void {
            $sub->where('city', 'like', $like)
                ->orWhere('postal_code', 'like', $like)
                ->orWhere('address', 'like', $like)
                ->orWhere('name', 'like', $like)
                ->orWhere('description', 'like', $like);

            if ($zipLike) {
                $sub->orWhere('postal_code', 'like', '%'.addcslashes(preg_replace('/\D+/', '', $searchBy), '%_\\').'%');
            }

            if ($stateCode !== null) {
                $sub->orWhereRaw('UPPER(state) = ?', [$stateCode]);
            } else {
                $sub->orWhere('state', 'like', $like);
            }
        });
    }

    private static function looksLikePostalCode(string $term): bool
    {
        $digits = preg_replace('/\D+/', '', $term);

        return $digits !== null && strlen($digits) >= 3 && strlen($digits) <= 10;
    }
}
