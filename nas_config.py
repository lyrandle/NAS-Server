import os
import subprocess
import sys

def setup_samba_share(share_dir, share_name):
    """Set up a Samba share."""
    try:
        samba_conf = f"""
[{share_name}]
   path = {share_dir}
   browseable = yes
   read only = no
   guest ok = yes
"""
        with open("/etc/samba/smb.conf", "a") as conf_file:
            conf_file.write(samba_conf)
        subprocess.run(["sudo", "systemctl", "restart", "smbd"], check=True)
        print(f"Samba share '{share_name}' created successfully.")
    except Exception as e:
        print(f"Error setting up Samba share: {e}")

def setup_nfs_share(share_dir, network="192.168.86.65/24"):
    """Set up an NFS share."""
    try:
        export_line = f"{share_dir} {network}(rw,sync,no_subtree_check)"
        with open("/etc/exports", "a") as exports_file:
            exports_file.write(export_line + "\n")
        subprocess.run(["sudo", "exportfs", "-ra"], check=True)
        subprocess.run(["sudo", "systemctl", "restart", "nfs-kernel-server"], check=True)
        print("NFS share created successfully.")
    except Exception as e:
        print(f"Error setting up NFS share: {e}")

if __name__ == "__main__":
    share_type = input("Enter share type (samba/nfs): ").strip().lower()
    share_dir = input("Enter the directory to share: ").strip()
    if not os.path.exists(share_dir):
        print("Directory does not exist.")
        sys.exit(1)
    
    if share_type == "samba":
        share_name = input("Enter Samba share name: ").strip()
        setup_samba_share(share_dir, share_name)
    elif share_type == "nfs":
        network = input("Enter the network for NFS clients (e.g., 192.168.1.0/24): ").strip()
        setup_nfs_share(share_dir, network)
    else:
        print("Invalid share type.")

