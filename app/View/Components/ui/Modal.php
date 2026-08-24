<?php

namespace App\View\Components\ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    // /**
    //  * Create a new component instance.
    //  */
    // public function __construct()
    // {
    //     //
    // }

    // /**
    //  * Get the view / contents that represent the component.
    //  */
    // public function render(): View|Closure|string
    // {
    //     return view('components.ui.modal');
    // }



        public $isOpen;
        public $showCloseButton;
        public $isFullscreen;
        public $modalId;
    
        /**
         * Create a new component instance.
         */
        public function __construct(
            $isOpen = false,
            $showCloseButton = true,
            $isFullscreen = false,
            $modalId = null
        ) {
            $this->isOpen = $isOpen;
            $this->showCloseButton = $showCloseButton;
            $this->isFullscreen = $isFullscreen;
            $this->modalId = $modalId ?? 'modal-' . uniqid();
        }
    
        /**
         * Get the view / contents that represent the component.
         */
        public function render(): View|Closure|string
        {
            return view('components.ui.modal');
        }
}
