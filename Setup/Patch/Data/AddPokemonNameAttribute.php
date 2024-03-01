<?php

declare(strict_types=1);

namespace Vovchenko\PokemonApi\Setup\Patch\Data;

use Exception;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Model\Product;
use Psr\Log\LoggerInterface;

class AddPokemonNameAttribute implements DataPatchInterface
{
    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly EavSetupFactory $eavSetupFactory,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Create product attribute pokemon_name
     *
     * @return void
     * @throw Exception
     */
    public function apply(): void
    {
        try {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
            $eavSetup->addAttribute(
                Product::ENTITY,
                'pokemon_name',
                [
                    'type' => 'varchar',
                    'label' => 'Pokemon Name',
                    'input' => 'text',
                    'required' => false,
                    'visible' => true,
                    'user_defined' => true,
                    'searchable' => true,
                    'filterable' => true,
                    'comparable' => true,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'General'
                ]
            );
        } catch (Exception $e) {
            $this->logger->critical('Create product attribute pokemon_name:' . ' ' . $e);
        }
    }

    /**
     * Get dependencies
     *
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Get aliases
     *
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
