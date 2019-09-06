<?php
/**
 * Created by PhpStorm.
 * User: Tom Herbers (dev@herbetom.de)
 * Date: 31.12.17
 * Time: 20:30
 */

require_once "config.php";

class getConfig extends config {
	/**
	 * @return string
	 */
	public function getSiteName(): string {
		return $this->siteName;
	}

	/**
	 * @return string
	 */
	public function getNavbarIcon(): string {
		return $this->navbarIcon;
	}

	/**
	 * @return bool
	 */
	public function isOutputError(): bool {
		return $this->outputError;
	}

	/**
	 * @return string
	 */
	public function getSiteBaseURL(): string {
		return $this->siteBaseURL;
	}

	/**
	 * @return string
	 */
	public function getSiteNameShort(): string {
		return $this->siteNameShort;
	}

	/**
	 * @return bool
	 */
	public function isMinify(): bool {
		return $this->minify;
	}

	/**
	 * @return string
	 */
	public function getDbHost(): string {
		return $this->dbHost;
	}

	/**
	 * @return string
	 */
	public function getDbName(): string {
		return $this->dbName;
	}

	/**
	 * @return string
	 */
	public function getDbUser(): string {
		return $this->dbUser;
	}

	/**
	 * @return string
	 */
	public function getDbPass(): string {
		return $this->dbPass;
	}

    /**
     * @return string
     */
    public function getOrganization(): string {
        return $this->organization;
    }

    /**
     * @return string
     */
    public function getOrganizationDomain(): string {
        return $this->organizationDomain;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getColorPrimary(): string
    {
        if (! isset($this->colorPrimary)) $this->colorPrimary = "#01579b";
        return $this->colorPrimary;
    }

    /**
     * @return string
     */
    public function getMiniplanLocalPath(): string
    {
        return $this->miniplanLocalPath;
    }

    /**
     * @return array array of strings containing the domains.
     */
    public function getDomain(): array
    {
        return $this->domain;
    }

    /**
     * @return string the e-mail address of the admin. Visible to all.
     */
    public function getAdminMail(): string
    {
        return $this->adminMail;
    }

    /**
     * @return int the current set log level
     */
    public function getLogLevel(): int
    {
        if (!isset($this->logLevel)) $this->logLevel=6;
        return $this->logLevel;
    }

    public function getLogLevelString($logLevel=-1)
    {
        if ($logLevel==-1) $logLevel = $this->getLogLevel();
        switch ($logLevel) {
            case 0: return "EMERGENCY"; break;
            case 1: return "ALERT"; break;
            case 2: return "CRITICAL"; break;
            case 3: return "ERROR"; break;
            case 4: return "WARNING"; break;
            case 5: return "NOTICE"; break;
            case 6: return "INFORMATIONAL"; break;
            case 7: return "DEBUG"; break;
            default: return "UNKNOWN";
        }
    }

}