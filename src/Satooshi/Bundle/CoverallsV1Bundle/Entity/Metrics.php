<?php

namespace Satooshi\Bundle\CoverallsV1Bundle\Entity;

/**
 * Metrics.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class Metrics
{
    /**
     * Number of statements.
     *
     * @var int
     */
    protected $statements;

    /**
     * Number of covered statements.
     *
     * @var int
     */
    protected $coveredStatements;

    /**
     * Line coverage.
     *
     * @var float
     */
    protected $lineCoverage;

    /**
     * Constructor.
     *
     * @param array $coverage coverage data
     */
    public function __construct(array $coverage = [])
    {
        if (!empty($coverage)) {
            // statements
            // not null
            $statementsArray = array_filter(
                $coverage,
                function ($line) {
                    return $line !== null;
                }
            );
            $this->statements = count($statementsArray);

            // coveredstatements
            // gt 0
            $coveredArray = array_filter(
                $statementsArray,
                function ($line) {
                    return $line > 0;
                }
            );
            $this->coveredStatements = count($coveredArray);
        } else {
            $this->statements = 0;
            $this->coveredStatements = 0;
        }
    }

    // API

    /**
     * Merge other metrics.
     *
     * @param Metrics $that
     */
    public function merge(Metrics $that)
    {
        $this->statements += $that->statements;
        $this->coveredStatements += $that->coveredStatements;
        $this->lineCoverage = null; // clear previous data
    }

    // internal method

    /**
     * Calculate line coverage.
     *
     * @param int $statements        number of statements
     * @param int $coveredStatements number of covered statements
     *
     * @return float
     */
    protected function calculateLineCoverage($statements, $coveredStatements)
    {
        if ($statements === 0) {
            return 0;
        }

        return ($coveredStatements / $statements) * 100;
    }

    // accessor

    /**
     * Return whether the source file has executable statements.
     *
     * @return bool
     */
    public function hasStatements()
    {
        return $this->statements !== 0;
    }

    /**
     * Return number of statements.
     *
     * @return int
     */
    public function getStatements()
    {
        return $this->statements;
    }

    /**
     * Return number of covered statements.
     *
     * @return int
     */
    public function getCoveredStatements()
    {
        return $this->coveredStatements;
    }

    /**
     * Return line coverage.
     *
     * @return float
     */
    public function getLineCoverage()
    {
        if (!isset($this->lineCoverage)) {
            $this->lineCoverage = $this->calculateLineCoverage($this->statements, $this->coveredStatements);
        }

        return $this->lineCoverage;
    }
}
