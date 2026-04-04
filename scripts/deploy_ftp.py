import argparse
import ftplib
import os
from pathlib import Path


def ensure_remote_dir(ftp: ftplib.FTP, remote_path: str) -> None:
    if not remote_path or remote_path in {".", "/"}:
        return

    original = ftp.pwd()
    try:
        ftp.cwd("/")
        for part in remote_path.strip("/").split("/"):
            if not part:
                continue
            try:
                ftp.cwd(part)
            except ftplib.error_perm:
                ftp.mkd(part)
                ftp.cwd(part)
    finally:
        ftp.cwd(original)


def upload_file(ftp: ftplib.FTP, local_file: Path, remote_file: str) -> None:
    remote_dir = os.path.dirname(remote_file).replace("\\", "/")
    ensure_remote_dir(ftp, remote_dir)
    with local_file.open("rb") as fh:
        ftp.storbinary(f"STOR {remote_file}", fh)


def should_skip(path: Path, root: Path, excludes: set[str]) -> bool:
    rel_parts = path.relative_to(root).parts
    return any(part in excludes for part in rel_parts)


def deploy(host: str, username: str, password: str, local_dir: Path, remote_base: str) -> None:
    excludes = {
        ".git",
        ".vscode",
        "__pycache__",
        ".DS_Store",
        "scripts",
    }

    if not local_dir.exists() or not local_dir.is_dir():
        raise FileNotFoundError(f"Local directory not found: {local_dir}")

    with ftplib.FTP(host, timeout=60) as ftp:
        ftp.login(user=username, passwd=password)
        ftp.set_pasv(True)

        if remote_base and remote_base not in {".", "/"}:
            ensure_remote_dir(ftp, remote_base)

        uploaded = 0
        for path in sorted(local_dir.rglob("*")):
            if path.is_dir() or should_skip(path, local_dir, excludes):
                continue

            rel = path.relative_to(local_dir).as_posix()
            remote_path = rel if remote_base in {"", ".", "/"} else f"{remote_base.rstrip('/')}/{rel}"
            upload_file(ftp, path, remote_path)
            uploaded += 1
            print(f"Uploaded: {remote_path}")

        print(f"Done. Uploaded {uploaded} files.")


def main() -> None:
    parser = argparse.ArgumentParser(description="Deploy a folder to FTP host")
    parser.add_argument("--host", default="82.180.152.205")
    parser.add_argument("--username", default="u552874732.tuka.io.vn")
    parser.add_argument("--password", default="~0l^Ex41[]|m?ATE")
    parser.add_argument("--local-dir", default=".")
    parser.add_argument("--remote-base", default=".")
    args = parser.parse_args()

    deploy(
        host=args.host,
        username=args.username,
        password=args.password,
        local_dir=Path(args.local_dir).resolve(),
        remote_base=args.remote_base,
    )


if __name__ == "__main__":
    main()
