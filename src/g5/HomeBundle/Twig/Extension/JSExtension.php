<?php

/**
 * This file is part of the mn-webapp package.
 *
 * (c) createproblem <https://github.com/createproblem/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace g5\HomeBundle\Twig\Extension;

class JSExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'toJS' => new \Twig_Function_Method($this, 'toJS', array(
                'is_safe' => array('html')
            )),
        );
    }

    /**
     * Exports a json string to valid javascript
     *
     * @param string $name The variable name
     * @param string $json The json data
     *
     * @return string
     */
    public function toJS($name, $json)
    {
        return '<script type="text/javascript">var '.$name.' = '.$json.';</script>';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'g5_js_extension';
    }
}
