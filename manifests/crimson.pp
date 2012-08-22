# required for Vagrant
group { 'puppet': 
    ensure => 'present',
}

package { 'epel-release':
    ensure => present,
    provider => rpm,
    source => 'http://dl.fedoraproject.org/pub/epel/6/i386/epel-release-6-7.noarch.rpm',
}

package { 'remi-release':
    ensure => present,
    provider => rpm,
    source => 'http://rpms.famillecollet.com/enterprise/remi-release-6.rpm',
    require => Package["epel-release"],
}

yumrepo { "remi":
    enabled => 1,
    require => Package["remi-release"],
}

$php = [ 
    "php",
    "php-cli",
    "php-common",
    "php-devel",
    "php-pear",
    "php-pecl-xdebug",
]

package { $php:
    ensure => installed,        
    provider => yum,        
    require => [Yumrepo["remi"]],
}

package { "git": 
    ensure => installed,
    provider => yum
}

exec { "composer":
    command => "/usr/bin/curl -s http://getcomposer.org/installer | php",
    cwd => "/vagrant",
    creates => "/vagrant/composer.phar",
    logoutput => $logoutput,
    require => [Package["php"], Package["git"]],
}

exec { "composer-install":
    command => "/usr/bin/php composer.phar install",
    cwd => "/vagrant",
    require => [Exec["composer"]],
}

exec { "composer-self-update":
    command => "/usr/bin/php composer.phar self-update",
    cwd => "/vagrant",
    require => [Exec["composer"]],
}
