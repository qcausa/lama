<?php
class Rkit_Pricelist extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'rkit-pricelist';
    }

    public function get_title()
    {
        return 'Pricing';
    }

    public function get_icon()
    {
        return 'rkit-widget-icon eicon-price-table';
    }

    public function get_categories()
    {
        return ['romethemekit_widgets'];
    }

    public function get_style_depends()
    {
        return ['rkit-pricelist-style'];
    }
    public function get_keywords()
    {
        return ['pricelist', 'time', 'rometheme'];
    }

    public function get_custom_help_url()
    {
        return 'https://rometheme.net/docs/';
    }
    protected function register_controls()
    {


        //description TEST
        //content 
        $this->start_controls_section('content_section_new', [
            'label' => esc_html__('Header'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT
        ]);

        // Nested repeater for description items


        $this->add_control(
            'card_title',
            [
                'label' => esc_html__('Title', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Type your title here', 'rometheme-for-elementor'),
                'default' => 'Basic',

            ]
        );

        $this->add_control('html_tag_pricing', [
            'label' => esc_html('Tag'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'h1' => esc_html('H1'),
                'h2' => esc_html('H2'),
                'h3' => esc_html('H3'),
                'h4' => esc_html('H4'),
                'h5' => esc_html('H5'),
                'h6' => esc_html('H6'),
            ],
            'default' => 'h3'
        ]);

        $this->add_control(
            'card_subheading',
            [
                'label' => esc_html__('Sub Heading', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Type your Sub Heading here', 'rometheme-for-elementor'),
                'default' => 'Sub Heading',

            ]
        );

        $this->add_control(
            'hr',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'show_sale_price',
            [
                'label' => esc_html__('Show Sale Price', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'rometheme-for-elementor'),
                'label_off' => esc_html__('Hide', 'rometheme-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'currency_icon',
            [
                'label' => __('Select Currency', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '$',
                'options' => [
                    '$' => __('$ - USD', 'rometheme-for-elementor'),
                    '€' => __('€ - EUR', 'rometheme-for-elementor'),
                    '¥' => __('¥ - JPY', 'rometheme-for-elementor'),
                    '¢' => __('¢ - CENT', 'rometheme-for-elementor'),
                    '₹' => __('₹ - INDIA', 'rometheme-for-elementor'),
                    '₽' => __('₽ - RUS', 'rometheme-for-elementor'),
                    '¥' => __('¥ - CNY', 'rometheme-for-elementor'),
                    '₠' => __('₠ - EUR', 'rometheme-for-elementor'),
                    '₣' => __('₣ - FRANC', 'rometheme-for-elementor'),
                    '₤' => __('₤ - LIRA', 'rometheme-for-elementor'),
                    '₥' => __('₥ - Mill', 'rometheme-for-elementor'),
                    '₱' => __('₱ - PESO', 'rometheme-for-elementor'),
                    '₩' => __('₩ - WON', 'rometheme-for-elementor'),
                    '฿' => __('฿ - BATH', 'rometheme-for-elementor'),
                    '﷼' => __('﷼ - Saudi Arabian', 'rometheme-for-elementor'),
                    'Rp' => __('Rp - IDR', 'rometheme-for-elementor'),
                    'costum' => __('Custum Currency', 'rometheme-for-elementor'),
                    // Tambahkan lebih banyak mata uang sesuai kebutuhan...
                ],

            ]
        );
        $this->add_control(
            'costum_currency',
            [
                'label' => esc_html__('Currency', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Currency', 'rometheme-for-elementor'),
                'condition' => [
                    'show_sale_price' => 'yes',
                    'currency_icon' => 'costum',
                ]
            ]
        );



        $this->add_control(
            'card_price',
            [
                'label' => esc_html__('Price', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Type your Price here', 'rometheme-for-elementor'),
                'default' => '26',
            ]
        );



        $this->add_control(
            'card_price_sale',
            [
                'label' => esc_html__('Sale Price', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Type your Price here', 'rometheme-for-elementor'),
                'default' => '33',
                'condition' => [
                    'show_sale_price' => 'yes',
                ]
            ],

        );


        $this->add_control(
            'currency_potition',
            [
                'label' => esc_html__('Sale Price Potition', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'column' => esc_html__('Top', 'rometheme-for-elementor'),
                    'row'  => esc_html__('inline', 'rometheme-for-elementor'),
                ],
                'default' => 'row',
                'condition' => [
                    'show_sale_price' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .price-container' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'card_sub_title',
            [
                'label' => esc_html__('Period', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Type your subtitle here', 'rometheme-for-elementor'),
                'default' => '/Month',
            ]
        );



        $this->add_control(
            'period_potition',
            [
                'label' => esc_html__('Period Potition', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'center' => esc_html__('Center', 'rometheme-for-elementor'),
                    'bottom'  => esc_html__('Bottom', 'rometheme-for-elementor'),
                ],
                'default' => 'center',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section('content_descs_new', [
            'label' => esc_html__('Feature'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT
        ]);


        $description_repeater = new \Elementor\Repeater();

        $this->add_control(
            'more_options_desc',
            [
                'label' => esc_html__('Description', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $description_repeater->add_control(
            'description_item',
            [
                'label' => esc_html__('Description Item', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Type your description item here', 'rometheme-for-elementor'),
            ]
        );

        $description_repeater->add_control(
            'description_icon',
            [
                'label' => esc_html__('Description Icon', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'rtmicon rtmicon-check',
                    'library' => 'rtmicons',
                ],
            ]
        );

        $this->add_control(
            'description_list',
            [
                'label' => esc_html__('Description List', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $description_repeater->get_controls(),
                'default' => [
                    ['description_item' => esc_html__('Description Item #1.', 'rometheme-for-elementor')],
                    ['description_item' => esc_html__('Description Item #2 ', 'rometheme-for-elementor')],
                    ['description_item' => esc_html__('Description Item #3 ', 'rometheme-for-elementor')],
                    ['description_item' => esc_html__('Description Item #4 ', 'rometheme-for-elementor')],



                ],
                'desc_field' => '{{{ description_item }}}',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section('content_button_new', [
            'label' => esc_html__('Button'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT
        ]);
        // Add controls for link, and button
        //divider control
        $this->add_control(
            'more_options',
            [
                'label' => esc_html__('Button', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_button_icon',
            [
                'label' => esc_html__('Show Button Icon', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'rometheme-for-elementor'),
                'label_off' => esc_html__('Hide', 'rometheme-for-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => esc_html__('Button Icon', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'label_block' => true,
                'default' => [
                    'value' => 'rtmicon rtmicon-check',
                    'library' => 'rtmicons',
                ],
                'condition' => [
                    'show_button_icon' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'button_icon_position',
            [
                'label' => esc_html__('Position', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'before' => [
                        'title' => esc_html__('Before Text', 'rometheme-for-elementor'),
                        'icon' => 'eicon-order-start',
                    ],
                    'after' => [
                        'title' => esc_html__('After Text', 'rometheme-for-elementor'),
                        'icon' => 'eicon-order-end',
                    ],
                ],
                'default' => 'after',
                'toggle' => true,
                'condition' => [
                    'show_button_icon' => 'yes'
                ]
            ]
        );


        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Buy Now', 'rometheme-for-elementor'),
            ]
        );

        $this->add_control('button_position', [
            'label' => esc_html('Button Position'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'top' => esc_html('Top'),
                'bottom' => esc_html('Bottom')
            ],
            'default' => 'bottom',
        ]);


        $this->add_control(
            'button_size_switch',
            [
                'label' => esc_html__('Full Size Button', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'no' => esc_html__('Default', 'rometheme-for-elementor'),
                'yes' => esc_html__('Full', 'rometheme-for-elementor'),
                'return_value' => 'yes',

            ]
        );


        $this->add_control(
            'card_link',
            [
                'label' => esc_html__('Link', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::URL,
                'options' => ['url', 'is_external', 'nofollow'],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('content_rib_new', [
            'label' => esc_html__('Ribbon'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT
        ]);
        //more
        $this->add_control(
            'more_options_ribbon',
            [
                'label' => esc_html__('Ribbon', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'enable_badge',
            [
                'label' => esc_html__('Enable Ribbon', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'rometheme-for-elementor'),
                'label_off' => esc_html__('No', 'rometheme-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Ribbon Text', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Best Seller', 'plugin-name'),
                'placeholder' => __('Enter badge text', 'plugin-name'),
                'condition' => [
                    'enable_badge' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'ribbon_position',
            [
                'label' => esc_html__('Position', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'rometheme-for-elementor'),
                        'icon' => 'eicon-order-start',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'rometheme-for-elementor'),
                        'icon' => 'eicon-order-end',
                    ],
                ],
                'default' => 'right',
                'toggle' => true,
                'condition' => [
                    'enable_badge' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('content_infob_new', [
            'label' => esc_html__('Additional Info'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT
        ]);

        $this->add_control(
            'card_footer',
            [
                'label' => esc_html__('Footer', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__('Type your  Footer here', 'rometheme-for-elementor'),
                'default' => 'Expired in 30 Days',

            ]
        );


        $this->end_controls_section();



        // style =======================================================================================================


        // style --------------------------------------------------------------------------------------------

        $this->start_controls_section('Container_style_section', [
            'label' => esc_html__('Container', 'rometheme-for-elementor'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);



        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'label' => __('Container Box Shadow', 'plugin-name'),
                'selector' => '{{WRAPPER}} .rkit-pricelist-container',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'con_border',
                'label' => esc_html__('Border Button', 'rometheme-for-elementor'),
                'selector' => '{{WRAPPER}} .rkit-pricelist-container',
            ]
        );

        $this->add_control(
            'con_padding',
            [
                'label' => esc_html__('Padding', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default' => [
                    'top' => 16,
                    'right' => 0,
                    'bottom' => 1,
                    'left' => 0,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),

            [
                'name' => 'cont_backgroud',
                'label' => esc_html__('Container Background', 'rometheme-for-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-inner'
                // 'selector' => '{{WRAPPER}} .rkit-pricelist-item-description, {{WRAPPER}} .rkit-pricelist-item-price-section, {{WRAPPER}} .rkit-pricelist-item-footer, {{WRAPPER}} .rkit-pricelist-item-button ',

            ]
        );

        $this->end_controls_section();


        // Style Section for Header
        $this->start_controls_section('title_style_section', [
            'label' => esc_html__('Header', 'rometheme-for-elementor'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);



        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Typography', 'rometheme-for-elementor'),
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-title',

            ]
        );
        $this->add_control(
            'title_align',
            [
                'label' => esc_html__('Alignment', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-title' => 'text-align: {{VALUE}};',
                ],
                'default' => 'center',
            ]
        );

        $this->add_control(
            'Header_padding',
            [
                'label' => esc_html__('Padding', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-title-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_spacing',
            [
                'label' => esc_html__('Spacing', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 2,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-title-section' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Text Color', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),

            [
                'name' => 'header_backgroud',
                'label' => esc_html__('Header Background', 'rometheme-for-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-title-section',

            ]
        );
        $this->add_control(
            'divider title',
            [
                'label' => esc_html__('Subheading', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        // Style Section for subheading


        $this->add_control(
            'subheading_color',
            [
                'label' => esc_html__('Color', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-sub-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'subheading_typography',
                'label' => esc_html__('Typography', 'rometheme-for-elementor'),
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-sub-heading',

            ]
        );

        $this->add_control(
            'subheading_align',
            [
                'label' => esc_html__('Alignment', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-sub-heading' => 'text-align: {{VALUE}};',
                ],
                'default' => 'center',
            ]
        );


        $this->end_controls_section();



        //style section sale price 
        $this->start_controls_section('sale_price', [
            'label' => esc_html__('Sale Price', 'rometheme-for-elementor'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'sale_price_typography',
                'label' => esc_html__('Typography', 'rometheme-for-elementor'),
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-sale-price',

            ]
        );
        $this->add_control(
            'sale_price_align',
            [
                'label' => esc_html__('Alignment', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .sale-price-container' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'currency_potition' => 'top',
                ],
                'default' => 'center',
            ]
        );

        $this->add_control(
            'sale_price_color',
            [
                'label' => esc_html__('Text  Color', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-sale-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'saleprice_spacing',
            [
                'label' => esc_html__('Spacing', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 2,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .price-container' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );



        $this->end_controls_section();

        //style section currency 
        $this->start_controls_section('currency_style_section', [
            'label' => esc_html__('Price', 'rometheme-for-elementor'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,

        ]);


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'currency_typography',
                'label' => esc_html__('Currency Typography', 'rometheme-for-elementor'),
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-currency',
                'default' => [
                    'font_family' => 'verdana',
                    'font_size' => '30px',
                    'font_weight' => '500',
                    'text_transform' => 'uppercase',
                ],

            ]
        );

        $this->add_control(
            'currency_color',
            [
                'label' => esc_html__('Text Currency Color', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-currency' => 'color: {{VALUE}};',
                ],
            ]
        );



        $this->add_control(
            'divider price',
            [
                'label' => esc_html__('price', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Style Section for price

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'label' => esc_html__('Price Typography', 'rometheme-for-elementor'),
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-price',
                'default' => [
                    'font_family' => 'verdana',
                    'font_size' => '41px',
                    'font_weight' => '500',
                    'text_transform' => 'uppercase',
                ],
            ]
        );
        $this->add_control(
            'price_color',
            [
                'label' => esc_html__('Text Price Color', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-price' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'price_backgroud',
                'label' => esc_html__('Price Background', 'rometheme-for-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-price-section',
            ]
        );

        $this->add_control(
            'price_padding',
            [
                'label' => esc_html__('Price Padding', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-price ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'period_spacing',
            [
                'label' => esc_html__('Spacing', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 2,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .period-opsi' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'divider price 2',
            [
                'label' => esc_html__('Period', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        //style section sub title

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'sub_title_typography',
                'label' => esc_html__('Typography', 'rometheme-for-elementor'),
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-sub-title , {{WRAPPER}} .rkit-pricelist-item-sub-title-center',

            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__('Color', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-sub-title , {{WRAPPER}} .rkit-pricelist-item-sub-title-center' => 'color: {{VALUE}};',
                ],
            ]
        );



        $this->add_control(
            'sub_title_align',
            [
                'label' => esc_html__('Alignment', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-sub-title , {{WRAPPER}} .rkit-pricelist-item-sub-title-center' => 'text-align: {{VALUE}};',
                ],
                'default' => 'center',
            ]
        );

        $this->add_control(
            'sub_title_padding',
            [
                'label' => esc_html__('Padding', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-sub-title , {{WRAPPER}} .rkit-pricelist-item-sub-title-center' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );



        $this->end_controls_section();



        // Style Section for Description
        $this->start_controls_section('description_style_section', [
            'label' => esc_html__('Feature', 'rometheme-for-elementor'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Text Color', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-description' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),

            [
                'name' => 'feat_backgroud',
                'label' => esc_html__('Feature Background', 'rometheme-for-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-description ',

            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__('Typography', 'rometheme-for-elementor'),
                'default' => [
                    'font_family' => 'amiko',
                    'font_size' => '16px',
                    'font_weight' => '500',
                    'text_transform' => 'cappitalize',
                    'line_height' => '37px',
                    'letter_spacing' => '-0.1px',
                    'word_spacing' => '2px',
                ],
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-description',

            ]
        );

        $this->add_control(
            'description_align',
            [
                'label' => esc_html__('Alignment', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-description' => 'text-align: {{VALUE}};',
                ],
                'default' => 'center',
            ]
        );



        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => esc_html__('Item Spacing', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 2,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-description' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'more_options_icon',
            [
                'label' => esc_html__('Icon', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Desc icon Color', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FF00C6',
                'selectors' => [
                    '{{WRAPPER}} .icon-list-feature' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'more_options_divider',
            [
                'label' => esc_html__('Divider', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_bottom_color',
            [
                'label'     => __('Divider Color', 'rometheme-for-elementor'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#bbb8b8',
                'selectors' => [
                    '{{WRAPPER}} .divider_desc' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'show_divider',
            [
                'label' => esc_html__('Show Divider', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'yes' => esc_html__('show', 'rometheme-for-elementor'),
                'no' => esc_html__('hide', 'rometheme-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );









        $this->end_controls_section();

        // Style Section for Button
        $this->start_controls_section('button_style_section', [
            'label' => esc_html__('Button', 'rometheme-for-elementor'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);



        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => esc_html__('Typography', 'rometheme-for-elementor'),
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-button .rkit-pricelist-item-button-full .elementor-button, {{WRAPPER}} .button-element-price',
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'label' => esc_html__('Border Button', 'textdomain'),
                'selector' => '  {{WRAPPER}} .button-element-price',
            ]
        );



        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-button, {{WRAPPER}}.rkit-pricelist-item-button-full, {{WRAPPER}} .button-element-price ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 15,
                    'right' => 0,
                    'bottom' => 25,
                    'left' => 3,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-button, {{WRAPPER}} .rkit-pricelist-item-button-full' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_align',
            [
                'label' => esc_html__('Alignment', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'textdomain'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'textdomain'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'textdomain'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-button' => 'text-align: {{VALUE}};',
                ],
                'default' => 'center',
            ]
        );
        $this->add_control(
            'more_options_icon_button_back',
            [
                'label' => esc_html__('Button Container Background', 'textdomain'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),

            [
                'name' => 'button_backgroud',
                'label' => esc_html__('Button Background', 'textdomain'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-button, {{WRAPPER}} .rkit-pricelist-item-button-full',

            ]
        );





        // $this->end_controls_section();

        // /wkwkwkw
        $this->start_controls_tabs('button_tab');

        $this->start_controls_tab('button_tab_normal', ['label' => esc_html('Normal')]);

        $this->add_control('button_text_color_normal', [
            'label' => esc_html('Text Color'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rkit-pricelist-item-button, {{WRAPPER}} .rkit-pricelist-item-button-full, {{WRAPPER}} a' => 'color : {{VALUE}}'
            ]
        ]);

        $this->add_control('button_icon_color_normal', [
            'label' => esc_html('Icon Color'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .icon-list-button' => 'color : {{VALUE}}'
            ]
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow_normal',
                'selector' => '{{WRAPPER}} .button-element-price, {{WRAPPER}} a',
            ]
        );

        $this->add_control(
            'btn_bg_options_normal',
            [
                'label' => esc_html__('Button Background', 'textdomain'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_background_normal',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .button-element-price, {{WRAPPER}} a',
                'default' => '#FF00C6',
            ]
        );




        $this->end_controls_tab();

        $this->start_controls_tab('button_tab_hover', ['label' => esc_html('Hover')]);

        $this->add_control('button_text_color_hover', [
            'label' => esc_html('Text Color'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .rkit-pricelist-item-button a:hover' => 'color : {{VALUE}}'
            ]
        ]);

        $this->add_control('button_icon_color_hover', [
            'label' => esc_html('Icon Color'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .icon-list-button a:hover ' => 'color : {{VALUE}}'
            ]
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btn_box_shadow_hover',
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-button a:hover',
            ]
        );

        $this->add_control(
            'btn_bg_options_hover',
            [
                'label' => esc_html__('Background', 'textdomain'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'btn_background_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-button a:hover',
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();





        // style for footer
        $this->start_controls_section('footer_style_section', [
            'label' => esc_html__('Footer', 'rometheme-for-elementor'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);


        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'footer_typography',
                'label' => esc_html__('Typography', 'rometheme-for-elementor'),
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-footer',
                'default' => [
                    'font_family' => 'verdana',
                    'font_size' => '12px',
                    'font_weight' => '300',
                    'text_transform' => 'uppercase',
                ],
            ]
        );

        $this->add_control(
            'footer_align',
            [
                'label' => esc_html__('Alignment', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'rometheme-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-footer' => 'text-align: {{VALUE}};',
                ],
                'default' => 'center',
            ]
        );


        $this->add_control(
            'footer_color',
            [
                'label' => esc_html__('Color', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-footer' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'footer_padding',
            [
                'label' => esc_html__('Padding', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricelist-item-footer ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),

            [
                'name' => 'footer_backgroud',
                'label' => esc_html__('Footer Background', 'rometheme-for-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .rkit-pricelist-item-footer ',

            ]
        );
        $this->end_controls_section();




        //wkwkwkw
        $this->start_controls_section(
            'ribbon_style_section',
            [
                'label' => __(' Ribbon Style', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'ribbon_typography',
                'label' => __('Typography', 'plugin-name'),
                'selector' => '{{WRAPPER}} .rkit-pricetable-ribbon__inner',
            ]
        );

        $this->add_control(
            'ribbon_text_color',
            [
                'label' => __('Text Color', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricetable-ribbon__inner' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'more_options_descmoocc',
            [
                'label' => esc_html__('Background Ribbon', 'rometheme-for-elementor'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'ribbon_distance',
            [
                'label' => esc_html__('Distance', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 2,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .rkit-pricetable-ribbon__inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg);',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),

            [
                'name' => 'ribbom_backgroud',
                'label' => esc_html__('Ribbon Background', 'rometheme-for-elementor'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .rkit-pricetable-ribbon__inner',
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'ribbon_border',
                'label' => esc_html__('Border  ', 'rometheme-for-elementor'),
                'selector' => '{{WRAPPER}} .rkit-pricetable-ribbon__inner',
            ]
        );




        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ribbon_box_shadow',
                'label' => __('Box Shadow', 'plugin-name'),
                'selector' => '{{WRAPPER}} .rkit-pricetable-ribbon__inner',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $decodedString = $settings['currency_icon'];
        $item_link = (!empty($settings['card_link']['url'])) ? esc_url($settings['card_link']['url']) : '#';
        // $item_link_target = !empty($settings['card_link']['is_external']) ? ' target="_blank"' : '';
        // $item_link_nofollow = !empty($settings['card_link']['nofollow']) ? ' rel="nofollow"' : '';
        // $button_size_switch = $this->get_settings_for_display('button_size_switch');



        switch ($settings['html_tag_pricelist']) {
            case 'h1':
                $html_tages = 'h1';
                break;
            case 'h2':
                $html_tages = 'h2';
                break;
            case 'h3':
                $html_tages = 'h3';
                break;
            case 'h4':
                $html_tages = 'h4';
                break;
            case 'h5':
                $html_tages = 'h5';
                break;
            case 'h6':
                $html_tages = 'h6';
                break;
            default:
                $html_tages = 'h1';
                break;
        }


        if ($settings['button_size_switch'] == 'yes') {
            $class_button = "button-full-size";
        } else {
            $class_button = "";
        }

        if ($settings['show_divider'] != 'yes') {
            $divider_show = "noline";
        } else {
            $divider_show = "";
        }

?>
        <div class="rkit-pricelist-container">
            <?php
            $badge_classes = 'rkit-pricelist-item';
            $wrap = 'rkit-wrap';
            $ribbon = '';
            $text_ribbon = $settings['badge_text'];



            // var_dump($settings['button_size']);

            ?>

            <div class="<?php echo esc_attr($badge_classes) ?>">
                <?php if ($settings['enable_badge'] === 'yes') { ?>
                    <div class="rkit-pricetable-ribbon rkit-pricetable-ribbon__<?php echo esc_html($settings['ribbon_position']); ?>">
                        <div class="rkit-pricetable-ribbon__inner">
                            <?php echo esc_html($settings['badge_text']) ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="rkit-pricelist-item-inner">
                    <div class="rkit-pricelist-item-title-section">
                        <?php if (!empty($settings['card_title'])) { ?>
                            <<?php echo esc_html($html_tages); ?> class="rkit-pricelist-item-title"><?php echo esc_html($settings['card_title']) ?> </<?php echo esc_html($html_tages); ?>>
                            <span class="rkit-pricelist-item-sub-heading"><?php echo esc_html($settings['card_subheading']) ?></span>
                        <?php  } ?>
                    </div>

                    <div class="rkit-pricelist-item-inner-price">
                        <div class="rkit-pricelist-item-price-section">


                            <?php if (!empty($settings['card_price'])) {  ?>
                                <div class="price-container">
                                    <?php if ($settings['show_sale_price'] == 'yes') {  ?>
                                        <div class="sale-price-container-inline">
                                            <?php if ($decodedString != 'costum') { ?>
                                                <p class="rkit-pricelist-item-sale-price"><?php echo esc_html($decodedString) ?></p>
                                            <?php } else { ?>
                                                <p class="rkit-pricelist-item-sale-price"><?php echo esc_html($settings['costum_currency']) ?></p>
                                            <?php } ?>
                                            <p class="rkit-pricelist-item-sale-price"><?php echo esc_html($settings['card_price_sale']) ?></p>
                                        </div>
                                    <?php } ?>

                                    <div class="sale-price-container-inline period-opsi">
                                        <?php if ($decodedString != 'costum') { ?>
                                            <div class="currency-option">
                                                <p class="rkit-pricelist-item-currency"><?php echo esc_html($decodedString) ?></p>
                                            <?php } else { ?>
                                                <p class="rkit-pricelist-item-currency"><?php echo esc_html($settings['costum_currency']) ?></p>
                                            <?php } ?>

                                            <p class="rkit-pricelist-item-price"><?php echo esc_html($settings['card_price']) ?></p>
                                            </div>
                                            <?php
                                            if ($settings['period_potition'] == 'center') {
                                                if (!empty($settings['card_sub_title'])) { ?>
                                                    <div class="period-option">
                                                        <p class="rkit-pricelist-item-sub-title-center"><?php echo esc_html($settings['card_sub_title']) ?></p>
                                                    </div>
                                            <?php   }
                                            }  ?>
                                    </div>


                                </div>
                            <?php } ?>

                            <?php if ($settings['period_potition'] == 'bottom') {
                                if (!empty($settings['card_sub_title'])) { ?>
                                    <div class="rkit-pricelist-item-sub-title"><?php echo esc_html($settings['card_sub_title']) ?></div>
                            <?php   }
                            }
                            ?>
                        </div>



                        <?php if ($settings['button_position'] == 'top') {
                            if (!empty($settings['button_text'])) {  ?>
                                <div class="rkit-pricelist-item-button <?php echo esc_html($class_button) ?>">
                                    <?php if ($settings['button_icon_position'] == "before") { ?>
                                        <a href="<?php $item_link ?>" class="elementor-button button-element-price">
                                            <?php \Elementor\Icons_Manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true', 'class' => "icon-list-button"]); ?>
                                            <?php echo esc_html($settings['button_text']) ?>
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php $item_link ?>" class="elementor-button button-element-price">
                                            <?php echo esc_html($settings['button_text']) ?>
                                            <?php \Elementor\Icons_Manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true', 'class' => "icon-list-button"]); ?>
                                        </a>
                                    <?php } ?>
                                </div>

                        <?php
                            }
                        } ?>


                        <?php if (!empty($settings['description_list'])) { ?>
                            <ul class="rkit-pricelist-item-description no-icon-hidden">
                                <?php foreach ($settings['description_list'] as $desc_item) { ?>

                                    <li class="divider_desc <?php echo esc_html($divider_show) ?>"> <?php \Elementor\Icons_Manager::render_icon($desc_item['description_icon'], ['aria-hidden' => 'true', 'class' => "icon-list-feature"]) ?> <?php echo  esc_html($desc_item['description_item']) ?></li>


                                <?php   } ?>
                            </ul>
                        <?php } ?>


                        <?php if ($settings['button_position'] == 'bottom') {
                            if (!empty($settings['button_text'])) {  ?>
                                <div class="rkit-pricelist-item-button <?php echo esc_html($class_button) ?>">
                                    <?php if ($settings['button_icon_position'] == "before") { ?>
                                        <a href="<?php $item_link ?>" class="elementor-button button-element-price">
                                            <?php \Elementor\Icons_Manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true', 'class' => "icon-list-button"]); ?>
                                            <?php echo esc_html($settings['button_text']) ?>
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php $item_link ?>" class="elementor-button button-element-price">
                                            <?php echo esc_html($settings['button_text']) ?>
                                            <?php \Elementor\Icons_Manager::render_icon($settings['button_icon'], ['aria-hidden' => 'true', 'class' => "icon-list-button"]); ?>
                                        </a>
                                    <?php } ?>
                                </div>
                            <?php  }
                        }

                        if (!empty($settings['card_footer'])) { ?>
                            <div class="rkit-pricelist-item-footer">
                                <p class="rkit-pricelist-item-footer"><?php echo esc_html($settings['card_footer']) ?></p>
                            </div>
                        <?php     } ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
<?php }
}
