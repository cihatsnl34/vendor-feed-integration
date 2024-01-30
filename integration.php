<?php

interface Vendor
{
    public function getFeedData(): array;
}

class VendorA implements Vendor
{
    public function getFeedData(): array
    {
        $vendorAData = [
            ['name' => 'Product A', 'price' => 20.99],
            ['name' => 'Product B', 'price' => 34.50],
        ];
        return $vendorAData;
    }
}

class VendorB implements Vendor
{
    public function getFeedData(): array
    {
        $vendorBData = [
            ['name' => 'Sweatshirt', 'size' => 'XL', 'price' => 21.99],
            ['name' => 'Short', 'size' => 'L', 'price' => 15.99],
        ];
        return $vendorBData;
    }
}
class VendorC implements Vendor
{
    public function getFeedData(): array
    {
        $vendorBData = [
            ['brand' => 'Golf', 'size' => 'HB', 'price' => 25000.00],
            ['brand' => 'Megane', 'size' => 'Sedan', 'price' => 22000.00],
        ];
        return $vendorBData;
    }
}

//Adapter Pattern
interface FeedAdapter
{
    public function adaptData(array $data): array;
}

class VendorAAdapter implements FeedAdapter
{
    public function adaptData(array $data): array
    {
        $adaptedData = [];

        foreach ($data as $item) {
            $adaptedData[] = [
                'product_name' => $item['name'],
                'product_size' => 'null',
                'product_price' => $item['price'],
            ];
        }

        return $adaptedData;
    }
}

class VendorBAdapter implements FeedAdapter
{
    public function adaptData(array $data): array
    {
        $adaptedData = [];

        foreach ($data as $item) {
            $adaptedData[] = [
                'product_name' => $item['name'],
                'product_size' => $item['size'],
                'product_price' => $item['price'],
            ];
        }
        return $adaptedData;
    }
}
class VendorCAdapter implements FeedAdapter
{
    public function adaptData(array $data): array
    {
        $adaptedData = [];

        foreach ($data as $item) {
            $adaptedData[] = [
                'product_name' => $item['brand'],
                'product_size' => $item['size'],
                'product_price' => $item['price'],
            ];
        }
        return $adaptedData;
    }
}

// Factory Pattern
class VendorFactory
{
    public static function createVendor(string $vendorName): Vendor
    {
        switch ($vendorName) {
            case 'VendorA':
                return new VendorA();
            case 'VendorB':
                return new VendorB();
            case 'VendorC':
                return new VendorC();
                // You can add cases to add more vendors.
            default:
                throw new Exception("Invalid Vendor name: $vendorName");
        }
    }
}

// Observer Pattern
class FeedObserver
{
    public function update(array $data): void
    {
        echo "Feed updated: ";

        foreach ($data as $item) {
            if (is_array($item)) {
                echo "[" . implode(', ', $item) . "] ";
            } else {
                echo $item . ' ';
            }
        }

        echo "\n";
    }
}

class IntegrationClient
{
    public function integrateVendorData(Vendor $vendor, FeedAdapter $adapter, FeedObserver $observer): void
    {
        $data = $vendor->getFeedData();
        $adaptedData = $adapter->adaptData($data);
        $observer->update($adaptedData);
    }
}

class IntegrationClientTest
{
    public function testIntegration(): void
    {
        try {
            $vendorName = 'VendorA';
            $vendor = VendorFactory::createVendor($vendorName);
            $adapter = new VendorAAdapter();
            $observer = new FeedObserver();

            $client = new IntegrationClient();
            $client->integrateVendorData($vendor, $adapter, $observer);

            echo "Test successful! Vendor integration worked.\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}

$test = new IntegrationClientTest();
$test->testIntegration();