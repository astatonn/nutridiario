<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    protected string $url;
    protected string $serviceKey;
    protected string $table = 'nutri_visitors';

    public function __construct()
    {
        $this->url = config('services.supabase.url');
        $this->serviceKey = config('services.supabase.service_key');
    }

    /**
     * Insert a new record into the nutridiario table
     */
    public function insertNutriDiario(array $data): ?array
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->serviceKey,
                'Authorization' => 'Bearer ' . $this->serviceKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation',
            ])->post("{$this->url}/rest/v1/{$this->table}", $data);

            if ($response->successful()) {
                Log::info('Supabase insert successful', ['data' => $data]);
                return $response->json();
            }

            Log::error('Supabase insert failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'data' => $data,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Supabase insert exception', [
                'message' => $e->getMessage(),
                'data' => $data,
            ]);
            return null;
        }
    }

    /**
     * Get all records from nutridiario table
     */
    public function getAllNutriDiario(): ?array
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->serviceKey,
                'Authorization' => 'Bearer ' . $this->serviceKey,
            ])->get("{$this->url}/rest/v1/{$this->table}?select=*&order=created_at.desc");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Supabase get all failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Supabase get all exception', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get a single record by ID
     */
    public function getNutriDiarioById(int $id): ?array
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->serviceKey,
                'Authorization' => 'Bearer ' . $this->serviceKey,
            ])->get("{$this->url}/rest/v1/{$this->table}?id=eq.{$id}&select=*");

            if ($response->successful()) {
                $data = $response->json();
                return $data[0] ?? null;
            }

            Log::error('Supabase get by ID failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'id' => $id,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Supabase get by ID exception', [
                'message' => $e->getMessage(),
                'id' => $id,
            ]);
            return null;
        }
    }

    /**
     * Get count of records in a date range
     */
    public function getCountByDateRange(?string $startDate = null): int
    {
        try {
            $url = "{$this->url}/rest/v1/{$this->table}?select=id";

            if ($startDate) {
                $url .= "&created_at=gte.{$startDate}";
            }

            $response = Http::withHeaders([
                'apikey' => $this->serviceKey,
                'Authorization' => 'Bearer ' . $this->serviceKey,
                'Prefer' => 'count=exact',
            ])->get($url);

            if ($response->successful()) {
                $contentRange = $response->header('Content-Range');
                if ($contentRange && preg_match('/\/(\d+)$/', $contentRange, $matches)) {
                    return (int) $matches[1];
                }
                return count($response->json());
            }

            return 0;
        } catch (\Exception $e) {
            Log::error('Supabase count exception', [
                'message' => $e->getMessage(),
            ]);
            return 0;
        }
    }
}
