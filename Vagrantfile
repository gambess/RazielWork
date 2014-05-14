Vagrant.configure("2") do |config|
    # Configure the box to use
    config.vm.box       = 'precise64'
    config.vm.box_url   = 'http://files.vagrantup.com/precise64.box'

    # Configure the network interfaces
    config.vm.network :private_network, ip:    "192.168.33.10"

    # Configure shared folders
    config.vm.synced_folder ".", "/vagrant", disabled: true
    config.vm.synced_folder ".", "/var/www/sgsd", type: "nfs"

    # Configure VirtualBox environment
    config.vm.provider :virtualbox do |v|
        v.name = "sgsd"
        v.customize [ "modifyvm", :id, "--memory", 1024 ]
    end

    # Provision the box
    config.vm.provision :ansible do |ansible|
        ansible.playbook = "ansible/site.yml"
        #ansible.inventory_path = "/home/juanda/Apps/SGSD/hosts"
    end
end
